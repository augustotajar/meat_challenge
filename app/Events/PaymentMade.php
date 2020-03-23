<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use  App\Payment;

class PaymentMade
{
    use SerializesModels;

    public $payment;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
