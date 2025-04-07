<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.perform');



Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');





