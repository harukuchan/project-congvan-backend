<?php

use Illuminate\Support\Facades\Route;
use App\Controllers\StudentController;

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
