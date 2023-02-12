<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mails\RequestReturnEmail;
use App\Libraries\Helpers;

class SendRequestReturnEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $email_admin = Helpers::get_setting('admin_email');
        $name_admin_email = env('MAIL_FROM_NAME');
        $subject_default = 'Olaben - Request Return Order: #' . $data['code'];

        $data['email_admin'] = $email_admin;
        $data['name_email_admin'] = $name_admin_email;
        $data['subject_default'] = $subject_default;

        $email = new RequestReturnEmail($data);
        Mail::send($email);
    }
}
