<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
 
class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $timestamp = time();
            $imageName = $timestamp.Str::random(32).".".$request->image_path->getClientOriginalExtension();
            Image::create([
                'img_id' => $request->img_id,
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
}