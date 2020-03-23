<?php

namespace App\Listeners;

use App\Events\PaymentMade;
use App\Http\Traits\CartTrait;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessPaidCart;

class CheckCartBalance
{
    use CartTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaymentMade  $event
     * @return void
     */
    public function handle(PaymentMade $event)
    {
        $payment  = $event->payment;
        $cart = $payment->cart;
        //If the balance its less or equal to zero it means the cart is paid.
        if($this->cartBalance($cart) <= 0){
            Log::debug("Cart $cart->id Paid!");
            if(empty($cart->paid_at)){
                ProcessPaidCart::dispatch($cart);
            }
        }

        return false;
    }
}
