<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\ProductStock;
use App\Models\Province;
use App\Models\Referral;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $list = User::with(
            [
                'referral' => function ($q) {
                    $q->select('*');
                }
            ]
        )
            ->orderBy('created_at', 'DESC')
            ->get();
        return view('admin.user.index', compact('list'));
    }

    public function detail($id)
    {
        $detail = User::with(
            [
                'referral' => function ($q) {
                    $q->select('*');
                }
            ]
        )
            ->where('id', $id)
            ->first();
        if ($detail) {
            $states = [];
            $cities = [];
            $districts = [];
            $wards = [];
            $countries = Country::orderBy('name', 'ASC')->get();
            if ($detail->country != '' && $detail->country != BaseConstants::VIETNAM_COUNTRY_ID) {
                $states = State::where('country_id', $detail->country)
                    ->orderBy('name', 'ASC')
                    ->get();
                if ($detail->state != '') {
                    $cities = City::where('state_id', $detail->state)
                        ->orderBy('name', 'ASC')
                        ->get();
                }
            } else {
                if($detail->country != '' && $detail->province != '') {
                    $districts = Address::LIST_DISTRICTS[$detail->province];
                }

                if ($detail->district != '') {
                    $wards = Address::LIST_WARDS[$detail->district];
                }
            }
            $provinces = Address::LIST_PROVINCES;

            $products = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
                ->where('status', BaseConstants::ACTIVE)
                ->get(
                    [
                        'product_stocks.id',
                        'product_stocks.title',
                        'product_stocks.slug'
                    ]
                );
            return view(
                'admin.user.single',
                compact('detail', 'countries', 'provinces', 'states', 'cities', 'wards', 'districts', 'products')
            );
        } else {
            return redirect()->route('admin.user.index');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'avatar' => 'mimes:jpg,jpeg,png|max:2048'
            ],
            [
                'name.required' => 'Name is required.',
                'name.max' => 'Name max 255 characters.',
                'avatar.mimes' => 'Format file not correct. Support .jpg, png, jpeg.',
                'avatar.max' => 'File size is to large. Max size 2MB'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = $request->id;

        $year = date('Y');
        $month = date('m');
        $date = date('d');
        $path_img = $year . '/' . $month . '/' . $date . '/';

        //avatar
        $name_field = "avatar";
        if ($request->avatar) {
            $file = $request->file($name_field);
            $name = time() . '-' . $file->getClientOriginalName();
            $name = str_replace(' ', '-', $name);
            $url_folder_upload = "/images/avatar/" . $path_img;
            $file->move(public_path() . $url_folder_upload, $name);
            $avatar = $path_img . $name;
        } elseif (isset($request->avatar_file_link) && $request->avatar_file_link != "") {
            $avatar = $request->avatar_file_link;
        } else {
            $avatar = "";
        }

        $data = [
            'name' => $request->name,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'address' => $request->address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'avatar' => $avatar,
            'status' => $request->status,
        ];

        if ($request->activeReferral == 1) {
            Referral::updateOrCreate(
                [
                    'user_id' => $id
                ],
                [
                    'status' => BaseConstants::ACTIVE,
                    'percent' => $request->percent,
                    'slug' => $request->slug,
                    'group_products' => ($request->group_products != '') ? json_encode($request->group_products) : json_encode([]),
                    'except_products' => ($request->except_products != '') ? json_encode($request->except_products) : json_encode([]),
                    'bank_info' => json_encode(
                        [
                           'bank_name' => $request->bank_name,
                           'account_name' => $request->account_name,
                           'account_number' => $request->account_number,
                           'swift' => $request->swift
                        ]
                    ),
                ]
            );
        } else {
            Referral::where('user_id', $id)->update(['status' => BaseConstants::INACTIVE]);
        }

        User::where("id", $id)->update($data);
        $msg = trans('messages.update_msg', ['model' => 'User']);
        return redirect()->route('admin.user.detail', array($id))->with('success_msg', $msg);
    }
}
