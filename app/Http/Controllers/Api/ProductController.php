<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_Category;
use App\Models\Supplier;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    //
     //
    public function index(Request $request){

        $perPage = $request->input('per_page', 10);
        $productlist = Product::with('category')->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $productlist,
        ])->withHeaders(['X-Total-Count' => $productlist->total()]);
    }


    public function getProduct(Request $request)
    {
        $productList = Product::with('category','suppliers', 'image');

        // Filter by minimum price
        if ($request->filled('minPrice')) {
            $productList->where('price', '>=', $request->input('minPrice'));
        }

        // Filter by search key
        if ($request->filled('searchKey')) {
            $searchKey = strtolower($request->input('searchKey'));
            $productList->where(function ($query) use ($searchKey) {
                $query->whereRaw('LOWER(name_pr) LIKE ?', ['%' . $searchKey . '%'])
                    ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $searchKey. '%']);
            });
        }

        // Filter by maximum price
        if ($request->filled('maxPrice')) {
            $productList->where('price', '<=', $request->input('maxPrice'));
        }

        // Filter by supplier name
        if ($request->filled('supplierId')) {
            $productList->whereHas('suppliers', function ($query) use ($request) {
                $query->where('supplier_id', $request->input('supplierId'));
            });
        }

        // Filter by category
        if ($request->filled('categoryId')) {
            $productList->whereHas('categories', function ($query) use ($request) {
                $query->where('product_category.category_id', $request->input('categoryId'));
            });
        }

        // Sort by name or price
        if ($request->filled('SortBy')) {
            $sortField = $request->input('SortBy');
            $IsDescending = $request->IsDescending;

            $productList->orderBy($sortField, $IsDescending=="true" ? 'desc' : 'asc');
        }
            // Get total product count for the filtered list
        // Pagination
        $pageNumber = ceil($request->filled('pageNumber') ? $request->pageNumber : 1);
        $pageSize = ceil($request->filled('pageSize') ? $request->pageSize : 12);

        // Calculate offset for pagination
        $offset = ($pageNumber - 1) * $pageSize;
        $totalProductCount = $productList->count();

        // Calculate total pages
        $totalPages = ceil($totalProductCount / 12);
            // Get paged product list using Eloquent offset and limit
        $pagedProductList = $productList->offset($offset)->limit($pageSize)->get();

        $response = [
            'Products' => $pagedProductList,
            'ProductsList is: ' => $productList,
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
            'TotalPages' => $totalPages,
            'TotalProducts' => $totalProductCount
        ];

        return response()->json($response);
    }

    public function addImageToProduct(Request $request)
    {
        try {
            $timestamp = time();
            $count = Image::where('product_id', $request->product_id)->count();
            $imageName = $request->product_id.'_'.$count.".".$request->image_path->getClientOriginalExtension();

            $randomId = 'IMG'.$timestamp;

            Image::create([
                'img_id' => $randomId,
                'product_id' => $request->product_id,
                'image_path' => $imageName
            ]);

            Storage::disk('public')->put($imageName, file_get_contents($request->image_path));
            return response()->json([
                'message' => "Post successfully created.",
                'status' => 200
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong!",
                'status' => 500
            ],500);
        }
    }
    public function getImagesByProductId($product_id){
        $images = Image::where('product_id',$product_id)->get();
        if ($images->isEmpty()) {
            return response()->json([
                'message' => 'no images found',
                'status' => 404
            ],404);
        }
        return response()->json($images);
    }

    public function showById(Request $request, $id){
        $product = Product::where('product_id', '=', $id)->with('image','suppliers','category')->first();
        try {
                return response()->json([
                    "product" => $product,
                ], 200);
                // return response()->json($id, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function showByName(Request $request){
        try {
            $product = Product::where('name_pr', 'LIKE', '%' . $request->name_pr . '%') ->get();
            return response()->json($product);
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
                    'message' => 'Product not found'
                ], 404); // 404 Not Found
            }

            $product->update($request->all());

            return response()->json([
                    'message' =>  "Product updated successfully",
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id){
        try{
            $product = Product::where('product_id', '=', $id)->first();
            $product->delete();
            $image = Image::where('product_id', '=', $id);
            $image->delete();
            return response()->json("successfully deleted the product", 200);
        } catch (\Throwable $th) {
            return response()->json("Error", 500);
        }
    }

    public function create(Request $req){
        $validator = Validator::make($req->all(), [
            'name_pr' => 'required|string',
            'name_serial' => 'required|string',
            'detail' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'quantity_pr' => 'required|numeric',
            'guarantee_period' => 'required|numeric',
            'supplier_id' => 'required| string'
        ]);


        if($validator -> fails()){
            return response () -> json([
                'error' => $validator -> errors(),
            ],422);
        }

        try {
            $randomId = 'Prod'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);

            $prod = Product::create([
                'product_id' => $randomId,
                'name_pr' => $req->name_pr,
                'name_serial' => $req->name_serial,
                'detail' => $req->detail,
                'price' => $req->price,
                'quantity_pr' => $req->quantity_pr,
                'guarantee_period' => $req->guarantee_period,
                'supplier_id' => $req->supplier_id
            ]);

            return response()->json($prod, 200);

        } catch (\Throwable $th){
            return response()->json($th->getMessage(), 500);
        }
    }

}
