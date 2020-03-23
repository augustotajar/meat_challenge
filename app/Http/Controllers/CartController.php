<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Cart;
use App\Product;
use App\Http\Traits\CartTrait;

class CartController extends Controller
{
    use CartTrait;

    /**
     * Display a listing of the carts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cart::with('products')->where('user_id',Auth::user()->id)->get();
    }

    /**
     * Creates a cart if it does not exists in storage and adds a product to the cart. 
     * The customer/user can only have one active cart. An active cart is a cart that has not been fully paid yet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'quantity' => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {              
            return response()->json($validator->errors(), 400);
        }

        $product_id = $request->id;
        $product = Product::findOrFail($product_id);
        $user = $request->user();
        $current_quantity = $this->countProductOnCustomersCart($user->id,$product_id);
        $cart = Cart::with('products')->where('user_id', $user->id)->whereNull('paid_at')->first();
        
        if(empty($cart)){
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->total = $product->price * $request->quantity;
            $cart->save();
        }
        
        //Updates the relationship
        $cart->products()->syncWithoutDetaching([$product_id => ['quantity' => $current_quantity + $request->quantity]]);
        $cart->total = $this->cartValue($cart);
        $cart->save();

        return response($cart->load('products'),201);
    }

    /**
     * Remove a certain quantity of a product from the customers cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'quantity' => 'required|integer|gt:0',
        ]);

        if ($validator->fails()) {              
            return response()->json($validator->errors(), 400);
        }

        $product_id = $request->id;
        Product::findOrFail($product_id);
        $user = $request->user();
        $current_quantity = $this->countProductOnCustomersCart($user->id,$product_id);  //Using trait
        $cart = Cart::with('products')->where('user_id', $user->id)->whereNull('paid_at')->first();
        
        if(empty($cart)){
            return response("Cart is empty",400);
        }
        
        $new_quantity = $current_quantity - $request->quantity;

        if($new_quantity <= 0){
            $cart->products()->detach($product_id);
        }else{
            $cart->products()->syncWithoutDetaching([$product_id => ['quantity' => $new_quantity]]);
        }

        $cart->total = $this->cartValue($cart); //Using trait
        $cart->save();

        return response($cart->load('products'),201);
    }

    /**
     * Remove the specified cart from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::where(['user_id' => Auth::user()->id, 'id' => $id])->first();
        if(!empty($cart)){
            $cart->products()->detach();
            $cart->delete();
            return response()->json("Cart deleted successfully",200);
        }
        
        return response()->json("Cart can not be found.",404);
    }
}
