<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function AddToCart(Request $req){

        // else {
        //     return response()->json($product, 200);
        // }
        try {
            $product = Product::where('product_id', $req->product_id)->first();
            if(!$product){
                return response()->json("Not found", 404);
            }

            $cart = Cart::where('product_id', $req->product_id)
                        ->where('user_id', $req->user_id)
                        ->first();
            if($cart){
                $cart->quantity += $req->quantity;
                $cart->update(['quantity' => $cart->quantity]);
            } else {
                $cart = Cart::create([
                    'user_id' =>  $req->user_id,
                    'product_id'=> $req->product_id,
                    'quantity' => $req->quantity
                ]);
            }
            return response() -> json($cart, 200);
        } catch (\Throwable $th){
            return response()->json($th->getMessage(), 500);
        }
    }

    public function UpdateQuantity(Request $req){
        try {

            $cart = Cart::where('product_id', $req->product_id)
                        ->where('user_id', $req->user_id)
                        ->first();
            if(!$cart){
                return response()->json("Not found", 404);
            }
            if($req->quantity == 0){
                $cart->delete();
                return response()->json("Successfully", 200);
            }
            else {
                $cart->quantity += $req->quantity;
                $cart->update(['quantity' => $cart->quantity]);
            }
            return response() -> json($cart, 200);
        } catch (\Throwable $th){
            return response()->json($th->getMessage(), 500);
        }
    }

    public function EmptyCart(Request $req){
        try {
            $cart = Cart::where('user_id', $req->user_id)->get();

            if ($cart->isEmpty()) {
                return response()->json("Cart is already empty", 200);
            }

            foreach ($cart as $Item) {
                $Item->delete();
            }

            return response()->json("Cart emptied successfully", 200);
        } catch(\Throwable $th){
            return response()->json($th->getMessage(), 200);
        }
    }
}