<?php

namespace App\Http\Controllers;

use App\Mail\MyMail;
use App\Models\File;
use App\Models\PasswordReset;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Token;
use SebastianBergmann\Environment\Console;

class UserController extends Controller
{
    public function register(Request $request){
            $user = new User;
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->confirmpassword = Hash::make($request->input('confirmPassword'));
            $user->gender = $request->input('gender');
            $user->name = $request->input('name');
            $user->phanquyen = 'nhanvien';
            $user->save();
            return response([
                'message' => 'success',
                'user' => json_encode($user),
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
                'user' => $user
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
    public function updatePassword(Request $request){
        $password = $request->password;
        $repassword = $request->repassword;
        if($password == $repassword){
            $input = $request->only('password','repassword');
            return response([
                'message' =>'success',
                'input' => $input,
            ]);
        }
        else
        {
            return response([
                'message' =>'error',
                'password' => $password,
                'repassword' => $repassword,
            ]);
        }
    }
    public function getUserNhanVien(){
        $user = User::where('phanquyen','nhanvien')->get();
        return response([
            'message' => 'success',
            'user' =>$user,
        ]);

    }
    public function editUserNhanVien(Request $request){
        $id = $request->input('id');
        $user = User::where('phanquyen','nhanvien')
        ->where('_id',$id)
        ->first();

        if($user != null ){
            $user->email = $request->input('email');
            $user->gender = $request->input('gender');
            $user->name = $request->input('name');
            $user->phanquyen = $request->input('phanquyen','nhanvien');
            $user->updated_at = new DateTime('now');
            $user->save();
            return response([
                'messages' => 'success',
                'user' => $user,
            ]);
        }
        else{
            return response([
                'message' => 'false',
            ]);
        }
    }
    public function deleteUserNhanVien(Request $request){
        $id = $request->input('id');
        $user = User::where('phanquyen','nhanvien')
        ->where('_id',$id)
        ->first();
        if($user != null ){
            $user->delete();
            return response([
                'messages' => 'success',
                'user' => $user,
            ]);
        }
        else{
            return response([
                'message' => 'false',
            ]);
        }
    }
    public function uploadCongVan(Request $request)
    {
        $file = new File();
        $file = $request->input('file');
        $fileName = $file->getClientOriginalName();

        $uploadDir = 'upload/';
        $fullpath = $uploadDir . $fileName;
        Storage::disk('s3')->put($fullpath, file_get_contents($file), 'public');

        $item = new File();
        $item->url = $fileName;
        $item->mimetype = $file->getClientMimeType();
        $item->author_id = auth()->id();
        $item->save();

        return response([
            'message' => 'success',
            'file' => $item,
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
