<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mails\PaymentEmailCustomer;
use App\Libraries\Helpers;

class SendPaymentEmailCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data_customer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data_customer)
    {
        $this->data_customer = $data_customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data_customer = $this->data_customer;
        $email_admin = Helpers::get_option_minhnn('emailadmin');
        $name_admin_email = Helpers::get_option_minhnn('name-admin');
        $data_customer['email_admin'] = $email_admin;
        $data_customer['name_email_admin'] = $name_admin_email;
        $data_customer['name'] = $data_customer['first_name'] . ' ' . $data_customer['last_name'];
        $email = new PaymentEmailCustomer($data_customer);
        Mail::send($email);
    }
}
