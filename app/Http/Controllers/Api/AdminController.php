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


    
    public function getRevenue()
    {
        $currentMonthStart = Carbon::now()->firstOfMonth();
        $currentMonthEnd = $currentMonthStart->copy()->endOfMonth();
        $lastMonthStart = $currentMonthStart->copy()->subMonth()->firstOfMonth();
        $lastMonthEnd = $lastMonthStart->copy()->endOfMonth();
    
        $ordersThisMonth = Order::whereBetween('create_order_at', [$currentMonthStart, $currentMonthEnd])->get();
        $ordersLastMonth = Order::whereBetween('create_order_at', [$lastMonthStart, $lastMonthEnd])->get();
    
        $thisMonthRevenue = $ordersThisMonth->sum(function ($order) {
            return $order->total - $order->delivery_fee - round(($order->discount / 100) * $order->total, 2);
        });
    
        $lastMonthRevenue = $ordersLastMonth->sum(function ($order) {
            return $order->total - $order->delivery_fee - round(($order->discount / 100) * $order->total, 2);
        });
    
        $percentDifference = ($lastMonthRevenue > 0) ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 100;
    
        return response()->json([
            'thisMonthRevenue' => $thisMonthRevenue,
            'lastMonthRevenue' => $lastMonthRevenue,
            'percentDifference' => round($percentDifference, 2),
        ]);
    }
        
    public function getRevenueByYear($year)
    {
        $endDate = now(); // Current date

        $labels = [];
        $revenues = [];

        for ($month = 1; $month <= $endDate->month; $month++) {
            $startDateOfMonth = now()->setYear($year)->setMonth($month)->startOfMonth();
            $endDateOfMonth = now()->setYear($year)->setMonth($month)->endOfMonth();

            $revenue = Order::whereBetween('create_order_at', [$startDateOfMonth, $endDateOfMonth])
                ->sum('total');

            $labels[] = $startDateOfMonth->format('F');
            $revenues[] = $revenue;
        }

        $result = [
            'labels' => $labels,
            'revenues' => $revenues,
        ];

        return response()->json($result);
    }

}
