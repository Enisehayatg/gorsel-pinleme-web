<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PinController;

// Ana sayfa (login ekranı)
Route::get('/', function () {
    return view('welcome'); // login sayfan welcome.blade.php
})->name('login');

// Giriş yapma (login işlemi)
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.perform');

// Çıkış yapma
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google ile giriş
Route::post('/login/google', [AuthController::class, 'loginWithGoogle'])->name('login.google');

// Kayıt ol sayfası
Route::get('/register', function () {
    return view('register'); // register.blade.php
})->name('register');

// Kayıt olma işlemi
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
    // Dashboard sayfası (giriş sonrası yönlendirme)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pin oluşturma sayfası
    Route::get('/add', function () {
        return view('add');
    })->name('add');

    // Kaydedilen pinler sayfası
    Route::get('/saved', function () {
        return view('saved');
    })->name('saved');

    // To Do List routes
    Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
    Route::get('/todo/add-with-image', [TodoController::class, 'index'])->name('todo.addWithImage');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::post('/todos/{todo}/image', [TodoController::class, 'uploadImage'])->name('todo.uploadImage');

    // Save image route
    Route::post('/save-image', [DashboardController::class, 'saveImage'])->name('save.image');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pin routes
    Route::post('/pins', [PinController::class, 'store'])->name('pins.store');
    Route::get('/user-pins/{userId}', [PinController::class, 'getUserPins'])->name('user.pins');
    Route::delete('/pins/{id}', [PinController::class, 'destroy'])->name('pins.destroy');
});
