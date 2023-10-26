<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $req){
        $validator = Validator::make($req -> all(),[
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
            'name' => $req['name'],
            'email' => $req['email'],
            'phone' => $req['phone'],
            'password' => Hash::make($req['password']),
            'role_id' => $req['role_id'], 
            'user_id' => $req['user_id']
        ]);
        return response () -> json([
            'message' => 'Registration successful',
            'data' => $user 
        ],200);
    }

    public function login(Request $req){
        $user = User::where('phone', $req->phone) -> first();
        if(!$user ||  !Hash::check($req->password,$user->password)){
            return response([
                'error' => 'phone number or password is not matched',
                'status' => 401
            ],401);
        }
        return $user;
    }
}
