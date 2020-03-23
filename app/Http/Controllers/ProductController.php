<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Get a validator for an incoming product request.
     *
     * @param  array  $data
     * @param  int  $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data,$id='')
    {
        return Validator::make($data, [
            'code' => 'required|string|max:50|unique:products,code,'.strval($id),
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $product = $request->all();
            $validator = $this->validator($product);

            if ($validator->fails()) {              
                return response()->json($validator->errors(), 400);
            }
             
            return response()->json(Product::create($product), 201);

        }catch(\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if(empty($product)){
            return response()->json("Product can not be found.",404);
        }

        return $product;
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $product = $request->all();
            $validator = $this->validator($product,$id);

            if ($validator->fails()) {              
                return response()->json($validator->errors(), 400);
            }

            $product = Product::where('id',$id)->first();

            if(empty($product)){
                return response()->json("Product can not be found.",404);
            }
            
            $product->code = $request->code;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->save();

            return response()->json($product, 200);

        }catch(\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Product::destroy($id)){
            return response()->json("Product deleted successfully",200);
        }
        
        return response()->json("Product can not be found.",404);
    }

}
