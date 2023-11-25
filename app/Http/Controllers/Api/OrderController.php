<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function AddNewOrder(Request $req){

        if (!$this->userHasPermissionToUpdate($req->user_id)) {
            return response()->json('Unauthorized to update this user', 401);
        }

        if(!User::where('user_id', $req->user_id))
            return response()->json("Not found user", 404);

        try {
            $randomId = 'Ord'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 7);
            $now = Carbon::now();
            $now->setTimezone('Asia/Bangkok');

            $order = Order::create([
                'order_id' => $randomId,
                'user_id' => $req->user_id,
                'create_order_at' => $now,
                'name' => $req->name,
                'email' => $req->email,
                'phone' => $req->phone,
                'address' => $req->address,
                'state' => $req->state,
                'note' => $req->note,
                'total' => 0,
                'discount' => $req->discount,
                'delivery_fee' => $req->delivery_fee
            ]);

            $total = 0;

            $cart = Cart::where('user_id', $req->user_id)->get();

            foreach($cart as $item){
                $new_quantity = 0;
                //Tính tổng tiền
                $product = Product::where('product_id', $item['product_id'])->first();
                $detail_price = $product->price * $item->quantity;
                $total += $detail_price;
                $new_quantity = $product->quantity_pr - $item->quantity;

                //Kiêm tra só lượng hàng còn đủ không
                if($new_quantity < 0){
                    $order->delete();
                    return response()->json("Số lượng hàng khách hàng muốn mua cao hơn số lượng hiện có", 422);
                }
                else
                    $product->update(['quantity_pr' => $new_quantity]);

                //Thêm vào detail_order
                $detail_order = DetailOrder::make([
                    'order_id' => $randomId,
                    'product_id' => $item->product_id,
                    'price_pr' => $product->price,
                    'quantity_pr' => $item->quantity,
                    'warranty period' => 12,
                ]);

                $detail_order->save();

                //Xoá khỏi cart
                $item->delete();
            }

            $order->update(['total' => $total]);

            return response()->json($order, 200);

        } catch (\Throwable $th){
            return response()->json($th->getMessage(), 500);
        }
    }

    public function UpdateStateOrder(Request $req)
    {
        $order = Order::where('order_id', $req->order_id);
        if(!$order)
            return response()->json("Not Found", 404);

        try {
            $order->update(['state' => $req->state]);
            return response()->json("order updated successfully", 200);
        } catch(\Throwable $th)
        {
            return response()->json($th->getMessage(), 500);
        }

    }

    public function GetAllOrder(Request $req)
    {
        $order = Order::with('CustumerInfor', 'discountInFor')->get();
        return response()->json($order, 200);
    }
    public function GetOrderByUserID(Request $req)
    {
        $order = Order::where('user_id', '=', $req->user_id)->with('CustomerInfor', 'discountInFor')->with('detail_order.product','detail_order.product.image')->get();
        return response()->json($order, 200);
    }

    protected function userHasPermissionToUpdate($id)
    {
        $currentUser = auth()->user();

        if ($currentUser->user_id == $id) {
            return true;
        }
        return false;
    }

}