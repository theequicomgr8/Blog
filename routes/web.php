<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('home');
// })->name('user.home');

Route::get('/',[UserController::class,'index'])->name('user.home');
Route::post('/signup',[UserController::class,'signup'])->name('user.signup');
Route::post('/login',[UserController::class,'login'])->name('user.login');

Route::group(["middleware"=>'myauth'],function(){
    Route::get('/profile',[UserController::class,'profile'])->name('user.profile');
    Route::post('/student-save',[UserController::class,'student_save'])->name('student.save');
    Route::get('/getState',[UserController::class,'getState'])->name('getState');
    Route::get('/getCity',[UserController::class,'getCity'])->name('getCity');
    Route::get('/logout',[UserController::class,'logout'])->name('logout');
});
