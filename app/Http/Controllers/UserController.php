<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function test(Request $request){
        $email = $request->email;
        $password = Hash::make($request->password);
        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->save();
        return response([
            'message' => 'success',
            'user' => $user,
        ]);
    }
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        if(Auth::attempt(['email' => $email, 'password' => $password]))
        {
            $user = Auth::user();
            return response([
                'message' => 'success',
                'user' => $user,
            ]);
        }
        else
    {
        return response([
            'message' => 'false'
        ]);
    }
    }
}
