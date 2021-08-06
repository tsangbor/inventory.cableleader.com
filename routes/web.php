<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Route::prefix('/')->name('dashboard.')->group(function () {
    Route::get('/login', [Auth\AuthController::class, 'login'])->name('login');
    Route::post('/login', [Auth\AuthController::class, 'postlogin'])->name('login.post');
    Route::get('/logout', [Auth\AuthController::class, 'logout'])->name('logout');


    Route::get('/', [HomeController::class, 'index'])->name('home.default')->middleware('auth');
    Route::get('/index', [HomeController::class, 'index'])->name('home.index')->middleware('auth');
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index')->middleware('auth');
    Route::get('/products/list', [ProductsController::class, 'list'])->name('products.list')->middleware('auth');

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index')->middleware('auth');
    Route::get('/orders/list', [OrdersController::class, 'list'])->name('orders.list')->middleware('auth');

    Route::get('/shipment', [ShipmentController::class, 'index'])->name('shipment.index')->middleware('auth');
    Route::get('/inventory/stock-in', [InventoryController::class, 'stockin'])->name('stockin.index')->middleware('auth');
    Route::get('/inventory/stock-record', [InventoryController::class, 'stockrecord'])->name('stockrecord.index')->middleware('auth');
});
