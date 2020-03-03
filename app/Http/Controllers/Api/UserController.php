<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    
    public function getUser(Request $request)
    {
        $users = User::orderBy('created_at', 'DESC')->get();

        return response()->json(['status' => 'success', 'users' => $users], 200);

    }

    public function postAddUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|unique:users'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make('12345678');
        $user->save();

        return response()->json(['message' => 'User Added'], 200);
    }
    
}
