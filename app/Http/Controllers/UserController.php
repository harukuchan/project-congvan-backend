<?php

namespace App\Http\Controllers;

use App\Mail\MyMail;
use App\Models\File;
use App\Models\Filenotres;
use App\Models\PasswordReset;
use App\Models\User;
use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{
    public function register(Request $request)
    {
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
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            return response([
                'message' => 'success',
                'user' => $user
            ]);
        } else {
            return response([
                'message' => 'false'
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->get();

        foreach ($user as $u) {
            $kiemtra = $u->email;
        }

        if ($email == $kiemtra) {
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
            $passwordReset->save();
            return response([
                'message' => 'success',
            ]);
        } else {
            return response([
                'message' => 'Your email address is incorrect',
            ]);
        }
    }
    public function confirmToken(Request $request)
    {
        $token = $request->token;
        $tokentable = PasswordReset::where('token', $token)->get();
        foreach ($tokentable as $t) {
            $tokentable = $t->token;
        }
        if ($tokentable === $token) {
            return response([
                'message' => 'Success',
            ]);
        } else {
            return response([
                'message' => 'your token is incorrect !',
            ]);
        }
    }
    public function updatePassword(Request $request)
    {
        $password = $request->password;
        $repassword = $request->repassword;
        if ($password == $repassword) {
            $input = $request->only('password', 'repassword');
            return response([
                'message' => 'success',
                'input' => $input,
            ]);
        } else {
            return response([
                'message' => 'error',
                'password' => $password,
                'repassword' => $repassword,
            ]);
        }
    }
    public function getUserNhanVien()
    {
        $user = User::where('phanquyen', 'nhanvien')->get();
        return response([
            'message' => 'success',
            'user' => $user,
        ]);
    }
    public function getUserThuThu()
    {
        $user = User::where('phanquyen', 'thuthu')->get();
        return response([
            'message' => 'success',
            'user' => $user,
        ]);
    }
    public function getUserGiamDoc()
    {
        $user = User::where('phanquyen', 'giamdoc')->get();
        return response([
            'message' => 'success',
            'user' => $user,
        ]);
    }
    public function getUserTruongPhong()
    {
        $user = User::where('phanquyen', 'truongphong')->get();
        return response([
            'message' => 'success',
            'user' => $user,
        ]);
    }
    public function editUserNhanVien(Request $request)
    {
        $id = $request->input('id');
        $user = User::where('phanquyen', 'nhanvien')
            ->where('_id', $id)
            ->first();

        if ($user != null) {
            $user->email = $request->input('email');
            $user->gender = $request->input('gender');
            $user->name = $request->input('name');
            $user->phanquyen = $request->input('phanquyen', 'nhanvien');
            $user->updated_at = new DateTime('now');
            $user->save();
            return response([
                'messages' => 'success',
                'user' => $user,
            ]);
        } else {
            return response([
                'message' => 'false',
            ]);
        }
    }
    public function deleteUserNhanVien(Request $request)
    {
        $id = $request->input('id');
        $user = User::where('phanquyen', 'nhanvien')
            ->where('_id', $id)
            ->first();
        if ($user != null) {
            $user->delete();
            return response([
                'messages' => 'success',
                'user' => $user,
            ]);
        } else {
            return response([
                'message' => 'false',
            ]);
        }
    }
    public function uploadCongVan(Request $request)
    {
        $date = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeAs('cv-response', $fileName, 's3');

        $files = new File();
        $files->namecongvan = $fileName;
        $files->mimetype = $file->getClientMimeType();
        $files->url = $path;
        $files->author_id = $request->input('role');
        $files->type = 'notapprove';
        $files->save();

        return response([
            'path' => $path,
            'file' => $files,
        ]);
    }
    public function uploadCVNotRes(Request $request)
    {
        $date = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $file = $request->file('filenot');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeAs('cv-not-response', $fileName, 's3');

        $files = new Filenotres();
        $files->namecongvan = $fileName;
        $files->mimetype = $file->getClientMimeType();
        $files->url = $path;
        $files->author_id = $request->file('role');
        $files->type = 'notapprove';
        $files->save();

        return response([
            'path' => $path,
            'file' => $files,
        ]);
    }
    public function getAllFile()
    {
        $file = File::all();
        return response([
            'message' => 'success',
            'file' => $file,
        ]);
    }
    public function getAllFileNotRes()
    {
        $file = Filenotres::all();
        return response([
            'message' => 'success',
            'file' => $file,
        ]);
    }
    public function getFileFromAWS(Request $request)
    {
        $fileName = $request->viewfile;
        if (Storage::disk('s3')->exists('cv-response/' . $fileName)) {
            $url = Storage::temporaryUrl('cv-response/' . $fileName, now()->addMinutes(5));
        }
        return  response([
            'message' => $fileName,
            'url' => $url,
        ]);
    }

    public function getFileNotResFromAWS(Request $request)
    {
        $fileName = $request->viewfile;
        if (Storage::disk('s3')->exists('cv-not-response/' . $fileName)) {
            $url = Storage::temporaryUrl('cv-not-response/' . $fileName, now()->addMinutes(5));
        }
        return  response([
            'message' => $fileName,
            'url' => $url,
        ]);
    }

    public function changeStatus(Request $request)
    {
        $filename = $request->filename;
        $author = $request->author_approved;
        $fileName = File::where('namecongvan', $filename)->first();
        $fileName->type = 'approved';
        $fileName->author_id = $author;
        $fileName->save();
        return response([
            'message' => 'success',
            'file' => $fileName,
        ]);
     }
     public function changeStatusNotRes(Request $request)
    {
        $filename = $request->filename;
        $author = $request->author_approved;
        $fileName = Filenotres::where('namecongvan', $filename)->first();
        $fileName->type = 'approved';
        $fileName->author_id = $author;
        $fileName->save();
        return response([
            'message' => 'success',
            'file' => $fileName,
        ]);
     }
}
