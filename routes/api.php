<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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


// Route::post('/auth/register',[UserController::class, 'register']);
// Route::post('/auth/login', [UserController::class,'login']);

Route::group([
    'middleware'=> ['api', 'cors'],
    'prefix' => 'auth'
], function(){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/userProfile', [AuthController::class, 'userProfile']);
    Route::post('/changePassword', [AuthController::class, 'changePassWord']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::middleware('checkAdmin')->group(function(){
        Route::get('/userAdmin', [AuthController::class, 'isAdmin']);
        Route::get('/index', [UserController::class, 'index']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });
});

Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => 'product'
], function(){
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'showById']);
    Route::get('/name/{id}', [ProductController::class, 'showByName']);
    Route::middleware('checkAdmin')->group(function(){
        Route::put('/update/{id}', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    });
});