<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstants;
use App\Models\Discount_code;
use App\Models\Address;
use App\Models\Order;
use App\Models\Rating_Product;
use App\Models\Wishlist;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Libraries\Helpers;

class UserController extends Controller
{
    public function registerForm()
    {
        return view('user.auth.register');
    }

    public function register(Request $request)
    {
        $validation_rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'birthday' => 'required',
            'phone' => 'required|unique:users',
            'address' => 'required'
        ];
        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email format is not correct.',
            'email.unique' => 'Email already used.',
            'birthday.required' => 'Birthday is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password at least 6 characters.',
            'password.confirmed' => 'Password confirmation is incorrect.',
            'phone.required' => 'Phone is required.',
            'phone.unique' => 'Phone number already used.',
            'address.required' => 'Address is required.'
        ];
        $validator = Validator::make($request->all(), $validation_rules, $messages);

        if($validator->fails()) {
            return  redirect()->back()->withErrors($validator);
        }

        $user = User::create(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'birthday' => $request->birthday,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'address' => $request->address,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'province' => $request->province,
                'district' => $request->district,
                'ward' => $request->ward,
                'status' => BaseConstants::ACTIVE,
            ]
        );

        if ($user) {
            if ($request->country != '' && $request->address != '') {
                $external = false;
                if ($request->country != BaseConstants::VIETNAM_COUNTRY_ID) {
                    $external = true;
                }
                Address::create(
                    [
                        'user_id' => $user->id,
                        'address' => $request->address,
                        'phone' => $user->phone,
                        'country' => $request->country,
                        'state' => $request->state,
                        'city' => $request->city,
                        'province' => $request->province,
                        'district' => $request->district,
                        'ward' => $request->ward,
                        'external' => $external,
                    ]
                );
            }

            Auth::login($user);

            $discountForNewUser = Helpers::get_option_minhnn('discount-for-new-user');
            if ($discountForNewUser != '' && $discountForNewUser > 0) {
                while (true) {
                    $code_discount = Helpers::auto_code_discount();
                    $checkCode = Discount_code::where('code', $code_discount)->first();
                    if (!$checkCode) {
                        break;
                    }
                }

                $date = date("d");
                $month = date("m");
                $year = date("Y");
                $hours = date("H");
                $min = date("i");
                $sec = date("s");
                $expired =date('Y-m-d H:i:s',mktime($hours,$min,$sec,$month,($date+60),$year));

                $group_code = [];
                $group_item = [
                    'percent' => $discountForNewUser,
                    'discount_money' => 0,
                    'apply_products' => [],
                    'except_products' => []
                ];
                array_push($group_code, $group_item);

                $discountCode = Discount_code::create(
                    [
                        'code' => $code_discount,
                        'expired' => $expired,
                        'start_date' => date('Y-m-d'),
                        'group_code' => json_encode($group_code),
                        'type' => 'onetime',
                        'status' => Discount_code::status['Active'],
                    ]
                );

                $data = array(
                    'name ' => $user->name,
                    'email' => $user->email,
                    'code_discount' => $discountCode->code,
                    'expired' => $discountCode->expired,
                    'subject_default' => 'Olaben - Voucher welcome to new members!'
                );

                Mail::send('email.user_register', $data,
                    function($message) use ($data) {
                        $message->from($data['email'], env('MAIL_FROM_NAME'));
                        $message->to($data['email'])
                            ->subject($data['subject_default']);
                    }
                );
            }
            return redirect()->route('user.dashboard');
        } else {
            return redirect()->back();
        }
    }

    public function loginForm()
    {
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->status == User::UserStatus['Inactive']) {
                    $msg = "Account has been banned! Please contact " . Helpers::get_setting('company_name') . " for more information.";
                    $url = route('index');
                    Helpers::msg_move_page($msg, $url);
                }
                Auth::login($user, $request->remember);

                return redirect()->route('index');
            } else {
                return redirect()->route('user.login')->withErrors('Password incorrect.');
            }
        } else {
            return redirect()->route('user.login')->withErrors('Email or Password incorrect.');
        }
    }

    public function dashboard()
    {
        return view('user.home');
    }

    public function profile()
    {
        $data = Auth::user();
        return view('user.profile', compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $validation_rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required'
        ];
        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'phone.required' => 'Phone is required.'
        ];

        $validator = Validator::make($request->all(), $validation_rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $id = Auth::user()->id;
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'about_me' => $request->about_me
        ];
        User::where("id", $id)->update($data);
        $msg = "User profile has been updated.";
        return redirect()->back()->with('success_msg', $msg);
    }

    public function wishlist()
    {
        $data = Wishlist::with(
            [
                'product' => function ($query) {
                    $query->select('*');
                }
            ]
        )
            ->where('user_id', Auth::user()->id)
            ->paginate(20);
        return view('user.wish-list', compact('data'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', Auth::user()->id)
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);
        return view('user.orders', compact('orders', 'user'));
    }

    public function orderDetail($id)
    {
        $detail = Order::with(
            [
                'order_details' => function ($query) {
                    $query->select('*');
                },
                'address' => function ($query) {
                    $query->select('*');
                },
                'order_return_history' => function ($query) {
                    $query->select('*');
                }
            ]
        )
            ->where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->first();
        if ($detail) {
            return view('user.order-detail', compact('detail'));
        } else {
            return redirect()->route('user.orders');
        }
    }

    public function reviews()
    {
        $data = Rating_Product::with(
            [
                'product' => function ($query) {
                    $query->select('id', 'title', 'slug', 'thumbnail');
                },
                'product.categories' => function ($query) {
                    $query->select('id', 'title', 'slug', 'thumbnail');
                    $query->first();
                }
            ]
        )
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return view('user.review', compact('data'));
    }

    public function changePasswordForm()
    {
        return view('user.change-password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        if (Hash::check($request->current_password, $user->password)) {
            if ($request->new_password == $request->confirm_password) {
                $data = array(
                    'password' => bcrypt($request->new_password)
                );
            } else {
                $msg = 'Password confirm incorrect.';
                return redirect()->back()->withErrors($msg);
            }
        } else {
            $msg = 'Current password incorrect.';
            return redirect()->back()->withErrors($msg);
        }
        User::where("id", $id)->update($data);
        $msg = "Password has been changed.";
        return redirect()->back()->with('success_msg', $msg);
    }

    public function storeReview(Request $request)
    {
        $product_id = $request->product_id;
        $rating = $request->rating;
        $check_rating = Rating_Product::where('product_id', $product_id)
            ->where('user_id', Auth::user()->id)
            ->get();
        if (count($check_rating) > 0) {
            return false;
        } else {
            Rating_Product::create(
                [
                    'product_id' => $product_id,
                    'user_id' => Auth::user()->id,
                    'rating' => $rating
                ]
            );
            return true;
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
