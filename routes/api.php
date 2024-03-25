<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductPending\ProductPendingController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserCar\UserCartController;
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

Route::group(['middleware' => ['auth']], function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
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
    Route::get('/get-detail-product/{product_code}',[ProductController::class, 'getDetailProduct']);
});

// Route::prefix('product')->group(function () {
//     Route::get('/get-product', [ProductController::class, 'getProduct'])->middleware('auth');
//     Route::get('/get-detail-product/{product_code}',[ProductController::class, 'getDetailProduct'])->middleware('auth');
// });


Route::group(['middleware' => ['auth']], function () {
    Route::prefix('cart-api')->group(function () {
        Route::get('/user-buys-product', [UserCartController::class, 'userBuysProduct']);
        Route::post('/post-detail-product-cart',[UserCartController::class, 'postDetailProductCart']);
        Route::get('/get-detail-product-cart/{product_code}',[UserCartController::class, 'getDetailProductCart']);
        Route::delete('/delete-detail-product-cart/{id}',[UserCartController::class, 'getDeleteDetailProductCart']);
        Route::post('/post-confirms-product',[UserCartController::class, 'postConfirmsProduct']);
    });

    Route::prefix('product-pending')->group(function () {
        Route::get('/get-product-pending', [ProductPendingController::class, 'getProductPending']);
        Route::post('/post-product-nextStep-pending',[ProductPendingController::class, 'handleCancelProduct']);
    });

    Route::prefix('export')->group(function () {
        Route::get('/export-excel-registration', [ProductPendingController::class, 'exportExcel']);
        Route::get('/export-pdf', [ProductPendingController::class, 'exportPdf']);
    });
});



