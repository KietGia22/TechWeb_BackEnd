<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $req){
       $req->validate([
        'email' => 'required|string|email',
        'password' => 'required|string|min:6',
       ]);

       $credentials = [
            'email' => $req->email,
            'password' => $req->password
        ];

       if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = JWTAuth::user();

        return response() -> json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => $user
        ], 200);
    }

    public function register(Request $req){
         $validator = Validator::make($req -> all(),[
            'user_id'=> 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if($validator -> fails()){
            return response () -> json([
                'message' => 'Validations fails',
                'error' => $validator -> errors(),
                'status' => 422
            ],422);
        }

        $user = User::create([
            'user_id' => $req->user_id,
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => Hash::make($req->password),
            'role' => $req->role
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response() -> json([
            'status' => 'success',
            'message' => 'User logged out successfully',
        ]);
    }

    public function userProfile()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'access_token' => Auth::refresh(),
            'data' => Auth::user()
        ]);
    }

}