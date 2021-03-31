<?php

namespace App\Http\Controllers;

use App\Mail\MyMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(Request $request){
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->gender = $request->input('gender');
        $user->address = $request->input('address');
        $user->city = $request->input('city');
        $user->gender = $request->input('gender');
        $user->birthday = $request->input('birthday');
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
    public function resetPassword(Request $request){
        $email = $request->email;

        $details = [
            'title' => 'Mail from CONG VAN SYSTEM',
            'body' => 'This is email for reset password'
        ];

        Mail::to($email)->send(new MyMail($details));
        return response([
            'message' => 'success',
        ]);

    }

    // public function index(){
    //     $user = 'Nghia';
    //     $array = array(
    //         'hj' => '1',
    //         'hk' => '2',
    //         'h3' => '3',
    //     );
    //     return view('user', compact('user','array'));
    // }
}
