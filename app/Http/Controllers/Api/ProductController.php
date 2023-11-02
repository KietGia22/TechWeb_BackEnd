<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        try {
            $product = Product::where('name_pr', 'LIKE', '%' . $id . '%')->with('categories')->get();
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
            $product = Product::where('product_id', '=', $id)->first();

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
        $product = Product::where('product_id', '=', $id)->first();
        $product->delete();
        $pr_ct = Product_Category::where('product_id', '=', $id);
        $pr_ct->delete();
        return response()->json([
            'status' => 'success',
            'message' => "successfully deleted the product",
        ]);
    }

    public function create(Request $req){
        $validator = Validator::make($req->all(), [
            'name_pr' => 'required|string',
            'name_serial' => 'required|string',
            'detail' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'quantity_pr' => 'required|numeric',
            'guarantee_period' => 'required|numeric',
            'category' => 'required|string',
            'supplier' => 'required| string'
        ]);


        if($validator -> fails()){
            return response () -> json([
                'message' => 'Validations fails',
                'error' => $validator -> errors(),
                'status' => 422
            ],422);
        }

        $randomId = 'Prod'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);
        $suppID = null;
        $CateID = null;

        $supp_ID = Supplier::where('supplier_name', '=', $req->supplier)->first();
        if($supp_ID == null){
            $supplier_random_Id = 'SUPPLIER'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);
            $sup = Supplier::create([
                'supplier_id' => $supplier_random_Id,
                'supplier_name' => $req->supplier
            ]);
            $suppID = $sup->supplier_id;
        } else {
            $suppID = $supp_ID->supplier_id;
        }

        $cate_ID = Category::where('category_name', '=', $req->category)->first();
        if($cate_ID == null){
            $cate_random_Id = 'CATE'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);
            $cate = Category::create([
                'category_id' => $cate_random_Id,
                'category_name' => $req->category
            ]);
            $CateID = $cate->category_id;

        } else {
            $CateID = $cate_ID->category_id;
        }

        $prod = Product::create([
            'product_id' => $randomId,
            'name_pr' => $req->name_pr,
            'name_serial' => $req->name_serial,
            'detail' => $req->detail,
            'price' => $req->price,
            'quantity_pr' => $req->quantity_pr,
            'guarantee_period' => $req->guarantee_period,
            'supplier_id' => $suppID
        ]);

        $ship = Product_Category::create([
                'category_id' => $CateID,
                'product_id' => $randomId
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $prod
        ]);
    }
}