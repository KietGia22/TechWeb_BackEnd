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

        try {
            $supp = Supplier::all();
            return response()->json($supp, 200);
        } catch(\Throwable $th) {
            return response()->json(
                $th->getMessage(), 404
            );
        }
    }

    public function showByName(Request $request, $name)
    {
        try{
            $supp = Supplier::where('supplier_name', 'LIKE', '%' . $name . '%')->with('products')->get();
            return response()->json($supp, 200);
        } catch (\Throwable $th) {
            return response()->json(
                $th->getMessage()
            , 500);
        }
    }

    public function showById(Request $request, $id)
    {
        try {
            $supp = Supplier::where('supplier_id', '=', $id)->get();
            return response()->json($supp, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $supp = Supplier::where('supplier_id', '=', $id)->first();

            if (!$supp) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404); // 404 Not Found
            }

            $supp->update($req->all());

            return response()->json($supp, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $req, $id)
    {
        $supp = Supplier::where('supplier_id', '=', $id)->first();
        $supp->delete();
        return response()->json([
            'status' => 'success',
            'message' => "successfully deleted the category",
        ]);
    }

    public function create(Request $req)
    {
        $randomId = 'SUPPLIER' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);

        $supplier = Supplier::create([
            'supplier_id' => $randomId,
            'supplier_name' => $req->supplier_name
        ]);

        return response()->json($supplier, 200);

    }
}
