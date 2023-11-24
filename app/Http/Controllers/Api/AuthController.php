<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChanged;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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
        'phone' => 'required|regex:/^[0-9]{10}$/',
        'password' => 'required|string|min:6',
       ]);

       $credentials = [
            'phone' => $req->phone,
            'password' => $req->password
        ];

       if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = JWTAuth::user();

        Session::put('access_token', $token);

        return response() -> json([
            'user' => $user,
            'token' => $token
        ],200)->withCookie(cookie('access_token', $token, 60));
    }

    public function register(Request $req){
         try {
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

        $now = Carbon::now();
        $now->setTimezone('Asia/Bangkok');

        $randomId = 'User'.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 3);

        $user = User::create([
            'user_id' => $randomId,
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => Hash::make($req->password),
            'role' => $req->role,
            'create_at' => $now
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ]);} catch (\Throwable $th) {
            return response()->json(
                $th->getMessage(), 500);
        }
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

    public function isAdmin()
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }

    public function changePassWord(Request $request) {
        // $validator = Validator::make($request->all(), [
        //     'old_password' => 'required|string|min:6',
        //     'new_password' => 'required|string|confirmed|min:6',
        // ]);

        // if($validator->fails()){
        //     return response()->json([
        //         'message' => 'Passwords do not match',
        //     ], 400);
        // }
        // $userId = Auth::user()->user_id;

        // $user = User::where('user_id', $userId)->update(
        //             ['password' => bcrypt($request->new_password)]
        //         );

        // return response()->json([
        //     'message' => 'User successfully changed password',
        //     'user' => $user,
        // ], 201);

        try{
            $email = $request->email;
            $user = User::where('email', $request->email)->first();

            if(!$user)
                return response()->json("User not found", 404);

            $createPassword = ''.substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 6);
            $newPassword = Hash::make($createPassword);
            $user->update(['password' => $newPassword]);

            Mail::to($email)->send(new PasswordChanged($createPassword, $user->name));
            return response()->json("Password Changed", 200);
        } catch (\Throwable $th)
        {
            return response()->json($th->getMessage(), 500);
        }
    }

}