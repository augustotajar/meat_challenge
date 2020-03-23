<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Cart;

class ProcessPaidCart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cart;
    public $tries = 3;

    /**
     * Create a new job instance.
     * 
     * @param \App\Cart
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug("Job init");
        $this->cart->paid_at = now();
        $this->cart->save();
        $this->cart->load('products');
        Log::debug(json_encode($this->cart));
        foreach($this->cart->products as $product){
            $product->stock -= $product->pivot->quantity;
            $product->save();
        }
    }
}
