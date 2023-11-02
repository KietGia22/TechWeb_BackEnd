<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index(Request $request){

        $perPage = $request->input('per_page', 10);
        $catelist = Category::with('products')->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => $catelist,
        ])->withHeaders(['X-Total-Count' => $catelist->total()]);
    }
}
