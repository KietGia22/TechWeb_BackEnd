<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class DetailOrderController extends Controller
{
    //
    public function GetOrderDetailByOrderID($req)
    {
        try {
            $order = DetailOrder::where('order_id', $req)
                    ->with('product')
                    ->with(['product', 'product.image'])
                    ->get();
            return response()->json($order, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
    public function addNewOrderDetail(Request $request)
    {
        $detailOrderData = $request->input('detailOrder');
        // Kiểm tra token nếu cần
        // ...

        // Lưu chi tiết đơn hàng vào database
        foreach ($detailOrderData as $detail) {
            DetailOrder::create($detail);
        }

        return response()->json(['message' => 'Order details added successfully'], 201);
    }
}