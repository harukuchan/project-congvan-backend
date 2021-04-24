<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register',[UserController::class,'register']);
Route::post('/loginuser',[UserController::class,'login']);

Route::post('/resetpassword',[UserController::class,'resetPassword']);
Route::post('/confirmtoken',[UserController::class,'confirmtoken']);
Route::post('/updatepassword',[UserController::class,'updatePassword']);
Route::get('/getusernhanvien', [UserController::class,'getUserNhanVien']);
Route::post('/editusernhanvien', [UserController::class,'editUserNhanVien']);
Route::post('/deleteusernhanvien', [UserController::class,'deleteUserNhanVien']);

Route::post('/uploadcongvan', [UserController::class,'uploadCongVan']);
Route::post('/uploadcongvannotres', [UserController::class,'uploadCVNotRes']);
