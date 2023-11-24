<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    //
    public function GetAllDiscount(Request $req)
    {
        try
        {
            $dis = Discount::get();
            return response()->json($dis, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function GetAllDiscount1(Request $req)
    {
        try
        {
            $dis = Discount::get();
            return response()->json("123", 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
