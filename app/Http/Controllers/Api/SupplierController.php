<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    //
    public function index(Request $request){
        $perPage = $request->input('per_page', 10);
        $supp = Supplier::with('products')->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $supp,
        ])->withHeaders(['X-Total-Count' => $supp->total()]);
    }

    public function showByName(Request $request, $name)
    {
        try{
            $supp = Supplier::where('supplier_name', 'LIKE', '%' . $name . '%')->with('products')->get();
            return response()->json([
                'status' => 200,
                'data' => $supp
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
            $supp = Supplier::where('supplier_id', '=', $id)->first();

            if (!$supp) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Category not found'
                ], 404); // 404 Not Found
            }

            $supp->update($req->all());

            return response()->json([
                    'status' => true,
                    'message' =>  "Category updated successfully",
                    'data' => $supp
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
        $supp = Supplier::where('category_id', '=', $id)->first();
        $supp->delete();
        return response()->json([
            'status' => 'success',
            'message' => "successfully deleted the category",
        ]);
    }

    public function create(Request $req)
    {
        $validator = Validator::make([
            'supplier_name' => 'required|string'
        ]);

        if($validator->failed()){
            return response () -> json([
                'message' => 'Validations fails',
                'error' => $validator -> errors(),
                'status' => 422
            ],422);
        }

        $randomId = 'SUPPLIER'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);

        $supplier = Supplier::create([
            'supplier_id' => $randomId,
            'suppler_name' => $req->supplier_name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $supplier
        ]);
    }
}
