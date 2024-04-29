<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
header('Access-Control-Allow-Origin', '*');
header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

Route :: post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum','admin')->group(function (){
    Route::get('/user', function (Request $request){
        return $request->user();
    });
    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/customers', CustomerController::class);
    Route::get('/countries', [CustomerController::class,'countries']);
    Route::get('/orders', [OrderController::class,'index']);
    Route::get('/orders/statuses', [OrderController::class,'getStatuses']);
    Route::post('/orders/change-status/{order}/{status}', [OrderController::class,'changeStatus']);
    Route::get('/orders/{order}', [OrderController::class,'view']);

});


