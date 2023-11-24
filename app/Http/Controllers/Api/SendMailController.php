<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChanged;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    //
    public function ForgetPassword(Request $request)
    {

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
