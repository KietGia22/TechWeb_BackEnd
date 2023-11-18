<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function index(Request $request){
        $categories = Category::all();
        return response()->json($categories);
    }

    public function showByName(Request $request, $name)
    {
        try{
            $cate = Category::where('category_name', 'LIKE', '%' . $name . '%')->with('products')->get();
            return response()->json([
                'data' => $cate
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function ShowByID(Request $req, $id){
        try {
            $cate = Category::where('category_id', '=', $id)->first();
            return response()->json($cate, 200);
        } catch (\Throwable $th) {
            return response()->json("Error", 500);
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $cate = Category::where('category_id', '=', $id)->first();

            if (!$cate) {
                return response()->json('Category not found', 404); // 404 Not Found
            }

            $cate->update($req->all());

            return response()->json($cate, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function destroy(Request $req, $id)
    {
        $cate = Category::where('category_id', '=', $id)->first();
        if(!$cate){
            return response()->json("Not Found", 404);
        }
        $cate->delete();
        return response()->json("Category deleted successfully", 200);
    }

    public function create(Request $req)
    {
        try {
             $validator = Validator::make($req->all(), [
                'category_name' => 'required|string',
            ]);

            if($validator->failed()){
                return response () -> json($validator -> errors(),422);
            }

            $randomId = 'CATE'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);

            $category = Category::create([
                'category_id' => $randomId,
                'category_name' => $req->category_name
            ]);

            return response()->json($category, 200);
        } catch(\Throwable $th){
            return response()->json($th->getMessage(), 500);
        }
    }
}
