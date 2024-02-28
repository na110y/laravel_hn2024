<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('info-user')->group(function () {
    Route::get('/get-info-user', [UserController::class, 'getInfoUser']);
    Route::get('/get-list-user', [UserController::class, 'getListUser']);
    Route::post('/update-info', [UserController::class, 'updateInfo']);
});

Route::prefix('product')->group(function () {
    Route::get('/get-product', [ProductController::class, 'getProduct']);
});