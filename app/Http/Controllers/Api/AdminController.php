<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function GetTotalCustomer(Request $req)
    {
        try
        {
            $user = User::where('role', '=', 'client')->count();
            return response()->json($user, 200);

        } catch(\Throwable $th)
        {
            return response()->json($th->getMessage(), 500);
        }

    }

    public function GetTotalOrder(Request $req)
    {
        try
        {
            $order = Order::get()->count();
            return response()->json($order, 200);

        } catch (\Throwable $th)
        {
            return response()->json($th->getMessage(), 500);
        }
    }
}