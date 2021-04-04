<?php

namespace App\Http\Controllers;

use App\Mail\MyMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Token;

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
        $user = User::where('email',$email)->get();
        foreach($user as $u){
            $kiemtra = $u->email;
        }

        if($email == $kiemtra)
        {
            $token = Str::random(10);
            $details = [
                'title' => 'Mail from CONG VAN SYSTEM',
                'body' => 'This is email for reset you password',
                'token' => $token,
            ];


            Mail::to($email)->send(new MyMail($details));
            $passwordReset = new PasswordReset();
            $passwordReset->email = $request->email;
            $passwordReset->token = $token;
            $passwordReset ->save();
            return response([
                'message' => 'success',
            ]);
        }
        else
        {
            return response([
                'message' => 'Your email address is incorrect',
            ]);
        }
    }
    public function confirmToken(Request $request){
        $token = $request->token;
        $tokentable = PasswordReset::where('token', $token)->get();
        foreach($tokentable as $t)
        {
            $tokentable = $t->token;
        }
        if($tokentable === $token)
        {
            return response([
                'message' => 'Success',
            ]);
        }
        else{
            return response([
                'message' =>'your token is incorrect !',
            ]);
        }
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
