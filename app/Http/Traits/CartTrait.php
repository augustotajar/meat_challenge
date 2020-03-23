<?php

namespace App\Http\Traits;

use App\Cart;

trait CartTrait 
{
    /**
     * Counts and returns the quantity of a product on a users cart.
     *
     * @param  int  $user_id
     * @param  int  $product_id
     * @return int
     */
    public function countProductOnCustomersCart($user_id, $product_id) {
        try{
            $quantity = 0;

            $cart = Cart::with(['products' => function ($query) use ($product_id) {
                //Querying the cart with the product we want to add
                $query->where('products.id', $product_id);
            }])->where('user_id', $user_id)->whereNull('paid_at')->first();
    
            if(!empty($cart->products)){
                $quantity = $cart->products[0]->pivot->quantity;
            }
    
            return $quantity;

        }catch(\Exception $e){
            return 0;
        }
    }

    /**
     * Returns the sum of all product prices.
     *
     * @param  \App\Cart  $cart
     * @return int
     */
    public function cartValue($cart) {
        try{
            $total = 0;

            if(!isset($cart->products)){
                $cart->load('products');
            }

            foreach($cart->products as $product){
                $total += $product->price * $product->pivot->quantity;
            }
            
            return $total;

        }catch(\Exception $e){
            return $total;
        }
    }
}