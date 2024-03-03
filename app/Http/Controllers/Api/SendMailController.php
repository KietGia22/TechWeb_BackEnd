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
    public function ForgetPassword($emails)
    {

        try{
            $email = $emails;
            $user = User::where('email', $emails)->first();

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