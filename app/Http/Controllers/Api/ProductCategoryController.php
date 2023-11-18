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
            $pr_cate = Product_Category::where('product_id', $req->product_id)
                ->where('category_id', $req->category_id)
                ->first();
            if($pr_cate){
                $pr_cate->update(['category_id' => $req->new_category_id]);
                return response()->json($pr_cate, 200);
            } else
                return response()->json("Not Found", 404);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function Delete_Link(Request $req)
    {
        try {
            $pr_cate = Product_Category::where('product_id', $req->product_id)
                ->where('category_id', $req->category_id)
                ->first();

            if ($pr_cate) {
                $pr_cate->delete();
                return response()->json("Deleted product_category by product_id and category_id", 200);
            } else {
                return response()->json("No matching record found for deletion", 404);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

}