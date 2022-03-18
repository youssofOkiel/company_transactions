<?php

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

Route::post('/register', [\App\Http\Controllers\auth\authController::class, 'register'])
    ->name('register');
Route::post('/login', [\App\Http\Controllers\auth\authController::class, 'login'])
    ->name('login');

Route::middleware(['auth:api'])->group( function () {
    Route::get('/logout', [\App\Http\Controllers\auth\authController::class, 'logout'])
        ->name('logout');
});

// categories
Route::prefix('admin')->middleware(['auth:api', 'admin'])->group( function () {

    Route::post('/createCategory', [\App\Http\Controllers\admin\categoryController::class, 'create'])
        ->name('createCategory');
    Route::get('/categories', [\App\Http\Controllers\admin\categoryController::class, 'mainCategories'])
        ->name('categories');
    Route::get('/subCategories/{parent_id}', [\App\Http\Controllers\admin\categoryController::class, 'subCategories'])
        ->name('subCategories');

});

// transactions
Route::prefix('admin')->middleware(['auth:api', 'admin'])->group( function () {

    Route::post('/createTransaction', [\App\Http\Controllers\admin\transactionController::class, 'create'])
        ->name('createTransaction');
    Route::get('/transactions', [\App\Http\Controllers\admin\transactionController::class, 'index'])
        ->name('transactions');

});

// payments
Route::prefix('admin')->middleware(['auth:api', 'admin'])->group( function () {

    Route::post('/recordPayment', [\App\Http\Controllers\admin\paymentController::class, 'create'])
        ->name('recordPayment');
    Route::get('/transactionPayment/{transaction_id}', [\App\Http\Controllers\admin\paymentController::class, 'show'])
        ->name('transactionPayment');

});

Route::prefix('customer')->middleware('auth:api')->group( function () {

    Route::get('/transactions', [\App\Http\Controllers\customer\transactionController::class, 'index'])
        ->name('transactions');

});
