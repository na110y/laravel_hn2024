<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductPending\ProductPendingController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserCar\UserCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::prefix('product')->group(function () {
    Route::get('/get-product', [ProductController::class, 'getProduct']);
    Route::get('/get-detail-product/{product_code}',[ProductController::class, 'getDetailProduct']);
});

// Route::prefix('product')->group(function () {
//     Route::get('/get-product', [ProductController::class, 'getProduct'])->middleware('auth');
//     Route::get('/get-detail-product/{product_code}',[ProductController::class, 'getDetailProduct'])->middleware('auth');
// });

// Khách hàng đăng nhập login
Route::group(['middleware' => ['auth']], function () {
    #Thông tin của khách hàng
    Route::prefix('info-user')->group(function () {
        Route::get('/get-info-user', [UserController::class, 'getInfoUser']);
        Route::get('/get-list-user', [UserController::class, 'getListUser']);
        Route::post('/update-info', [UserController::class, 'updateInfo']);
    });

    # Thông tin sản phẩm trong giỏ hàng
    Route::prefix('cart-api')->group(function () {
        Route::get('/user-buys-product', [UserCartController::class, 'userBuysProduct']);
        Route::post('/post-detail-product-cart',[UserCartController::class, 'postDetailProductCart']);
        Route::get('/get-detail-product-cart/{product_code}',[UserCartController::class, 'getDetailProductCart']);
        Route::delete('/delete-detail-product-cart/{id}',[UserCartController::class, 'getDeleteDetailProductCart']);
        Route::post('/post-confirms-product',[UserCartController::class, 'postConfirmsProduct']);
    });

    # Sản phẩm trong trong giai đoạn chờ duyệt hủy
    Route::prefix('product-pending')->group(function () {
        Route::get('/get-product-pending', [ProductPendingController::class, 'getProductPending']);
        Route::post('/post-product-nextStep-pending',[ProductPendingController::class, 'handleCancelProduct']);
    });

    # Xuất file thông tin sản phẩm
    Route::prefix('export')->group(function () {
        Route::get('/export-excel-registration', [ProductPendingController::class, 'exportExcel']);
        Route::get('/export-pdf', [ProductPendingController::class, 'exportPdf']);
    });
});


// Chỉ vai trò admin mới được quyền chỉnh sửa thông tin
Route::group(['middleware' => ['auth.admin']], function () {

    # Thông tin của khách hàng
    Route::prefix('info-user')->group(function () {
        Route::get('/get-all-info-user', [UserController::class, 'getAllInfoUser']);
        Route::delete('/delete-detail-user/{id}',[UserController::class, 'deleteDetailUser']);

    });

    # Next step duyệt sản phẩm của khách hàng
    Route::prefix('product-pending')->group(function () {
        Route::get('/get-product-pending', [ProductPendingController::class, 'getProductPending']);
        Route::post('/post-product-nextStep-pending',[ProductPendingController::class, 'handleCancelProduct']);
    });

    # xuất ra file thông tin của tất cả sản phẩm của khách hàng
    Route::prefix('export')->group(function () {
        Route::get('/export-excel-registration', [ProductPendingController::class, 'exportExcel']);
        Route::get('/export-pdf', [ProductPendingController::class, 'exportPdf']);
    });
});



