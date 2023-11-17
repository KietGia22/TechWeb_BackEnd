<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product_Category;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    //
    public function Create_Link(Request $req)
    {
        try{
            $pr_cate = Product_Category :: create([
                "product_id" => $req->product_id,
                "category_id" => $req->category_id
            ]);
            return response()->json($pr_cate, 200);
        } catch(\Throwable $th){
            return response()->json($th->getMessage(), 500);
        }
    }

    public function Update_Link(Request $req)
    {
        try {
            $pr_cate = Product_Category :: where ('product_id', '=', $req->product_id)
                        ->orWhere('category_id', '=', $req->category_id)->first();
            $pr_cate->update($req->all());
            return response()->json($pr_cate, 200);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function Delete_Link(Request $req)
    {
        try {
            $pr_cate = Product_Category :: where ('product_id', '=', $req->product_id)
                        ->orWhere('category_id', '=', $req->category_id)->first();
            $pr_cate->delete();
            return response()->json("Deleted product_category by product_id", 200);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}