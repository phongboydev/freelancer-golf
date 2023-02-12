<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mails\PaymentEmail;
use App\Libraries\Helpers;


class SendPaymentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data_admin;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data_admin)
    {
        $this->data_admin = $data_admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data_admin = $this->data_admin;

        $email_admin = Helpers::get_option_minhnn('emailadmin');
        $cc_email = Helpers::get_option_minhnn('toemail');
        $name_admin_email = Helpers::get_option_minhnn('name-admin');
        $subject_default = Helpers::get_option_minhnn('title-email-card');
        $data_admin['email_admin'] = $email_admin;
        $data_admin['cc_email'] = $cc_email;
        $data_admin['name_email_admin'] = $name_admin_email;
        $data_admin['subject_default'] = $subject_default;

        $email = new PaymentEmail($data_admin);
        Mail::send($email);
    }
}
