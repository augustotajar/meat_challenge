<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Payment;
use App\Cart;
use App\Events\PaymentMade;

class PaymentController extends Controller
{
    /**
     * Get a validator for an incoming payment request.
     *
     * @param  array  $data
     * @param  int  $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data,$id='')
    {
        return Validator::make($data, [
            'type' => 'required|string|max:255',
            'amount' => 'required|integer|gt:0',
            'cart_id' => 'required|integer',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Payment::with('cart')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $payment = $request->all();
            $validator = $this->validator($payment);

            if ($validator->fails()) {              
                return response()->json($validator->errors(), 400);
            }

            Cart::findOrFail($request->cart_id);
            $payment = Payment::create($payment);
            event(new PaymentMade($payment));

            return response()->json($payment, 201);

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json("Cart not found.",404);
        }catch(\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with('cart')->find($id);

        if(empty($payment)){
            return response()->json("Payment can not be found.", 404);
        }

        return $payment;
    }

}
