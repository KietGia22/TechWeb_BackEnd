<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $user = User::all();
        return response()->json($user, 200);
    }

   public function update(Request $request, $id)
    {
        if (!$this->userHasPermissionToUpdate($id)) {
            return response()->json('Unauthorized to update this user', 401);
        }

        try {
            $user = User::where('user_id', '=', $id)->first();

            if (!$user) {
                return response()->json('User not found', 404); // 404 Not Found
            }

            $user->update($request->all());

            return response($user, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); // 500 Internal Server Error
        }
    }

    protected function userHasPermissionToUpdate($id)
    {
        $currentUser = auth()->user();

        if ($currentUser->user_id == $id) {
            return true;
        }
        return false;
    }


    public function destroy($id)
    {
        $user = User::where('user_id', '=', $id)->first();
        if($user == null)
        {
            return response()->json('User not found', 404);
        }
        return response()->json('successfully deleted the user', 200);
    }
}