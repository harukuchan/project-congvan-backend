<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        $password = Hash::make($request->password);
        $user = User::where('email', $email)->where('password', $password)->get();
    }
}
