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
        $categories = Category::all('category_id');
        return response()->json($categories);
    }

    public function showByName(Request $request, $name)
    {
        try{
            $cate = Category::where('category_name', 'LIKE', '%' . $name . '%')->with('products')->get();
            return response()->json([
                'status' => 200,
                'data' => $cate
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $cate = Category::where('category_id', '=', $id)->first();

            if (!$cate) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Category not found'
                ], 404); // 404 Not Found
            }

            $cate->update($req->all());

            return response()->json([
                    'status' => true,
                    'message' =>  "Category updated successfully",
                    'data' => $cate
                ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $req, $id)
    {
        $cate = Category::where('category_id', '=', $id)->first();
        $cate->delete();
        $pr_ct = Product_Category::where('category_id', '=', $id);
        $pr_ct->delete();
        return response()->json([
            'status' => 'success',
            'message' => "successfully deleted the category",
        ]);
    }

    public function create(Request $req)
    {
        $validator = Validator::make([
            'category_name' => 'required|string'
        ]);

        if($validator->failed()){
            return response () -> json([
                'message' => 'Validations fails',
                'error' => $validator -> errors(),
                'status' => 422
            ],422);
        }

        $randomId = 'CATE'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);

        $category = Category::create([
            'category_id' => $randomId,
            'category_name' => $req->category_name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category
        ]);
    }
}