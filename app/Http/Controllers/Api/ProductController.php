<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
     //
    public function index(Request $request){

        $perPage = $request->input('per_page', 10);
        $productlist = Product::with('categories')->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $productlist,
        ])->withHeaders(['X-Total-Count' => $productlist->total()]);
    }

    public function showById(Request $request, $id){
        $product = Product::where('product_id', '=', $id)->with('categories')->first();
        try {
                return response()->json([
                    'status' => 200,
                    'data' => $product
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function showByName(Request $request, $id){
        $product = Product::where('name_pr', 'LIKE', '%' . $id . '%')->with('categories')->get();
        try {
                return response()->json([
                    'status' => 200,
                    'data' => $product
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $product = Product::where('product_id', '=', $id)->with('categories')->first();

            if (!$product) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Product not found'
                ], 404); // 404 Not Found
            }

            $product->update($request->all());

            return response()->json([
                    'status' => true,
                    'message' =>  "Product updated successfully",
                    'data' => $product
                ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id){
        $product = Product::where('product_id', '=', $id)->with('categories')->first();
        $product->delete();
        return response()->json([
            'status' => 'success',
            'message' => "successfully deleted the user",
        ]);
    }

    public function create(Request $request, $id){

    }
}