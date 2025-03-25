<?php

use App\Facades\CustomRoute;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

CustomRoute::customResource('/admin/order', OrderController::class, 'admin.order');
