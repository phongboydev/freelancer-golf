<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreOrderDiscount extends Mailable
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
        return $this->view('email.pre-order')
            ->with("details",$this->details)
            ->to($this->details['email'])
            ->from($this->details['email'], $this->details['name_email_admin'])
            ->replyTo($this->details['email_admin'], $this->details['name_email_admin'])
            ->subject($this->details['subject_default']);
    }
}
