<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstants;
use App\Models\Cart;
use App\Models\EmailContact;
use App\Models\User;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('home');
    }

    public function completeOrder(Request $request){
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            Cart::where('user_id', $user_id)->delete();
        } else {
            $request->session()->forget('cart');
        }
        return view('order.complete-page');
    }

    public function notFound()
    {
        return view('errors.404');
    }

    public function pageContact()
    {
        return view('page.contact');
    }

    public function storeContact(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'your_name' => 'required',
                'your_email' => 'required|email',
                'your_mobile' => 'required',
                'your_message' => 'required',
            ],
            [
                'your_name.required' => 'Name is required.',
                'your_email.required' => 'Email is required.',
                'your_email.email' => 'Email format is not correct.',
                'your_mobile.required' => 'Phone is required.',
                'your_message.required' => 'Message is required.',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email_admin = Helpers::get_setting('admin_email');
        $name_admin_email = env('MAIL_FROM_NAME');
        $cc_email = Helpers::get_setting('cc_mail');
        $subject_default = Helpers::get_option_minhnn('title-email-contact');
        $data = array(
            'your_name' => $request->your_name,
            'your_email' => $request->your_email,
            'your_mobile' => $request->your_mobile,
            'your_message' => $request->your_message,
            'email_admin' => $email_admin,
            'cc_email' => $cc_email,
            'name_email_admin' => $name_admin_email,
            'subject_default' => $subject_default
        );

        Mail::send(
            'email.contact',
            $data,
            function ($message) use ($data) {
                $message->from($data['email_admin'], $data['name_email_admin']);
                $message->to($data['email_admin'])
                    ->cc($data['cc_email'], $data['name_email_admin'])
                    ->subject($data['subject_default']);
            }
        );
        return redirect()->route('pageContact')
            ->with('success_msg', 'Thank you for contact us. We will contact to you soon as soon!');
    }

    public function signUpForAConsultation(Request $request)
    {
        if($request->email != ""){
            EmailContact::updateOrCreate(
                [
                    'email' => $request->email
                ],
                [
                    'register' => BaseConstants::ACTIVE,
                ]
            );
            return redirect()->back()->with('success_msg', 'Cảm ơn bạn đã đăng ký nhận tư vấn!');
        } else{
            return redirect()->back()->with('error_msg', 'Vui lòng nhập email.');
        }
    }
}
