<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiDetailController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| Web Routes
|-------------------------------------------------------------------------- 
| 
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
| 
*/

// Route untuk halaman depan
Route::get('/', function () {
    return view('welcome');
});

// Group untuk LoginController dengan middleware guest (hanya untuk yang belum login)
Route::controller(LoginController::class)->group(function() {
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

// Semua route di bawah ini memerlukan autentikasi (middleware auth)
Route::middleware(['auth'])->group(function () {

    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Group route untuk transaksi
    Route::prefix('/transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('store', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('delete/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });

    // Group route untuk transaksi detail
    Route::prefix('/transaksidetail')->group(function () {
        Route::get('/', [TransaksiDetailController::class, 'index'])->name('transaksidetail.index');
        Route::get('/{id_transaksi}', [TransaksiDetailController::class, 'detail'])->name('transaksidetail.detail');
        Route::get('edit/{id}', [TransaksiDetailController::class, 'edit'])->name('transaksidetail.edit');
        Route::put('update/{id}', [TransaksiDetailController::class, 'update'])->name('transaksidetail.update');
        Route::delete('delete/{id}', [TransaksiDetailController::class, 'destroy'])->name('transaksidetail.destroy');
    });

});