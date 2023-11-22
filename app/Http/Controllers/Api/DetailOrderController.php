<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class DetailOrderController extends Controller
{
    //
    public function GetOrderDetailByOrderID(Request $req)
    {
        try {
            $order = DetailOrder::where('order_id', $req->order_id)
                    ->with('product_id')
                    ->with(['product_id', 'product_id.image'])
                    ->get();
            return response()->json($order, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}