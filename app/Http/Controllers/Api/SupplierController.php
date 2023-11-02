<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
}
