<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use App\Mail\PreOrderDiscount;

class SendPreOrderDiscount implements ShouldQueue
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
        $email = new PreOrderDiscount($this->data_customer);
        Mail::send($email);
    }
}
