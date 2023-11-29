<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use GuzzleHttp\Psr7\Response;
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


    public function CreateDiscount(Request $req){
        try {
            $randomId = 'DISC' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 5);
            $discount =  Discount::create([
                'discount_id' => $randomId,
                'discount_code' => $req->discount_code,
                'discount_amount' => $req->discount_amount,
                'discount_date' => $req->discount_date,
                'discount_date_to' => $req->discount_date_to,
            ]);
            return response()->json($discount, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function UpdateDiscount(Request $req, $id)
    {
        try {
            $dis = Discount::where('discount_id', $id)->first();

            if(!$dis)
                return response()->json("Not Found", 404);

            $dis->update($req->all());

            return response()->json($dis, 200);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function DeleteDiscount(Request $req, $id){
        try {
            $dis = Discount::where('discount_id', $id)->first();

            if(!$dis)
                return response()->json("Not Found", 404);

            $dis->delete();

            return response()->json("successfully deleted the discount", 200);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}