<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\customerController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/login', function(){
    return 'login';
});

Route::post('/login', [AuthController::class, 'login'])->middleware(['register_log']);

Route::controller(customerController::class)
->prefix('/customer')
//->middleware(['check', 'check_customer'])
->group(function (){
    Route::get('index', 'index')->middleware(['check', 'register_log']);
    Route::post('create', 'createCustomer')->middleware(['check', 'check_customer', 'register_log']);
    Route::get('getcustomer/{id}', 'getCustomer')->middleware(['check', 'check_get_customer', 'register_log']);
    Route::delete('deletecustomer/{id}', 'deleteCustomer')->middleware(['check', 'check_get_customer', 'register_log']);
});

//Route::get('/customer/index', [customerController::class, 'index'])->middleware('check');
