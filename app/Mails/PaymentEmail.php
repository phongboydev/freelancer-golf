<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->details;
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
        return $this->view('email.cart')
            ->with("details", $data)
            ->to($data['email_admin'])
            ->from($data['email'], $data['name_email_admin'])
            ->subject("Đơn hàng mới từ: ". $data['name']);
    }
}
