<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailContact;

class EmailContactController extends Controller
{
    public function __construct()
    {
    }

    public function welcome()
    {
        $list = EmailContact::where('welcome', BaseConstants::ACTIVE)
            ->get();
        return view('admin.email-contact.welcome', compact('list'));
    }

    public function omYourInbox()
    {
        $list = EmailContact::where('register', BaseConstants::ACTIVE)
            ->get();
        return view('admin.email-contact.om-your-inbox', compact('list'));
    }
}
