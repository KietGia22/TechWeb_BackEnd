<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    //
    public function AddToCart(Request $req){

        // else {
        //     return response()->json($product, 200);
        // }
        try {

            if (!$this->userHasPermissionToUpdate($req->user_id)) {
                return response()->json('Unauthorized to update this user', 401);
            }

            $product = Product::where('product_id', $req->product_id)->first();
            if(!$product){
                return response()->json("Not found", 404);
            }

            $cart = Cart::where('product_id', $req->product_id)
                        ->where('user_id', $req->user_id)
                        ->first();
            if($cart){
                $cart->quantity += $req->quantity;
                $cart->update();
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
            if (!$this->userHasPermissionToUpdate($req->user_id)) {
                return response()->json('Unauthorized to update this user', 401);
            }

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
            if (!$this->userHasPermissionToUpdate($req->user_id)) {
                return response()->json('Unauthorized to update this user', 401);
            }

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

    protected function userHasPermissionToUpdate($id)
    {
        $currentUser = auth()->user();

        if ($currentUser->user_id == $id) {
            return true;
        }
        return false;
    }

    public function getCartProduct(Request $request)
    {
    $productsInCart = Cart::with(['product.image', 'product.category', 'product.suppliers'])
        ->where('user_id', $request->user_id)
        ->get();

    return response()->json($productsInCart);
    }

    public function getUserTotalProduct(Request $request)
    {
        $user_id = $request->input('user_id');

        $totalProduct = Cart::where('user_id', $user_id)->count();

        return response()->json($totalProduct);
    }
}
