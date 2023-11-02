<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Requests\PostStoreRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/auth/register',[UserController::class, 'register']);
Route::post('/auth/login', [UserController::class,'login']);
Route::post('postImage',[ImageController::class,'store']);
Route::get('/images/{product_id}',[ImageController::class,'getImagesByProductId']);