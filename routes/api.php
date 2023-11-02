<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        Route::get('/index', [ApiUserController::class, 'index']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });
});

//Product
Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => 'product'
], function(){
    Route::get('/', [ApiProductController::class, 'index']);
    Route::get('/{id}', [ApiProductController::class, 'showById']);
    Route::get('/name/{id}', [ApiProductController::class, 'showByName']);
    Route::middleware('checkAdmin')->group(function(){
        Route::post('/createProduct', [ApiProductController::class, 'create'] );
        Route::put('/update/{id}', [ApiProductController::class, 'update']);
        Route::delete('/delete/{id}', [ApiProductController::class, 'destroy']);
    });
});

//Category
Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => 'category'
], function(){
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'showByName']);
    Route::middleware('checkAdmin')->group(function(){
        Route::post('/create', [CategoryController::class, 'create']);
        Route::put('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });
});

//Suppliers
Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => 'supplier'
], function(){
    Route::middleware('checkAdmin')->group(function(){
        Route::get('/', [SupplierController::class, 'index']);
        Route::get('/{id}', [SupplierController::class, 'showByName']);
        Route::post('/create', [SupplierController::class, 'create']);
        Route::put('/update/{id}', [SupplierController::class, 'update']);
        Route::delete('/delete/{id}', [SupplierController::class, 'destroy']);
    });
});