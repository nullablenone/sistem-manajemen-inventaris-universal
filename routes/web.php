<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\StockLedgerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Middleware 'isLogin' memastikan user yang sudah login tidak bisa mengakses halaman ini lagi
Route::middleware(['isLogin'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('attributes', AttributeController::class);
    Route::resource('products', ProductController::class);

    // Stock Mutation Logic (Pilar B)
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/inbound', [StockTransactionController::class, 'inboundIndex'])->name('inbound.index');
        Route::get('/inbound/create', [StockTransactionController::class, 'inboundCreate'])->name('inbound.create');
        Route::post('/inbound', [StockTransactionController::class, 'inboundStore'])->name('inbound.store');

        Route::get('/outbound', [StockTransactionController::class, 'outboundIndex'])->name('outbound.index');
        Route::get('/outbound/create', [StockTransactionController::class, 'outboundCreate'])->name('outbound.create');
        Route::post('/outbound', [StockTransactionController::class, 'outboundStore'])->name('outbound.store');

        Route::get('/ledger', [StockLedgerController::class, 'index'])->name('ledger.index');
    });
});

