<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    public function getRevenueByDay()
    {
        $startDate = Carbon::today()->startOfWeek();
        $endDate = Carbon::today();

        $revenueByDay = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $revenue = Order::whereDate('create_order_at', $date->toDateString())->sum('total');

            $label = $date->format('l');

            $revenueByDay[$label] = $revenue;
        }

        $result = [
            'day' => array_keys($revenueByDay),
            'revenue' => array_values($revenueByDay),
        ];

        return response()->json($result);
    }
}
