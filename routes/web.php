<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::get('/', function(){
    return view('home');
});

// Route::prefix('greeting')->group(function () {

// 	// work for: /greeting/vn
//     Route::get('vn', function () {
//         $hello =
//             array("Khanh"=>27, "Đức"=>32, "Huyền"=>35, "Thúy"=>30);
//         return  json_encode($hello);
//     });

//     // work for: /greeting/en   
//     Route::get('en', function () {
//         return "Hello!";    
//     });

//     // work for: /greeting/cn
//     Route::get('cn', function () {
//         return "你好!";
//     });
// });

// Route::match(['get', 'post'], '/student', function (Request $request) {
//     return 'method is ' . $request->method();
// }); ---------- request method
Route::get('/home/{name?}',[HomeController::class,'index'])->name('home.index');
Route::get('/users',[UserController::class,'index'])->name('users.index');
