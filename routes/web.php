<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebtCollectionController;
use App\Http\Controllers\AuthController;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('check.login')->group(function () {
    Route::get('/', [DebtCollectionController::class, 'index'])->name('debt-collections.index');
    Route::get('/bao-cao-ngay', [DebtCollectionController::class, 'baoCaoNgay'])->name('debt-collections.bao-cao-ngay');
    Route::get('/them-moi', [DebtCollectionController::class, 'create'])->name('debt-collections.create');
    Route::post('/them-moi', [DebtCollectionController::class, 'store'])->name('debt-collections.store');
    Route::post('/thu/{debtCollection}', [DebtCollectionController::class, 'thu'])->name('debt-collections.thu');
    Route::post('/chuyen-thang/{debtCollection}', [DebtCollectionController::class, 'chuyenThang'])->name('debt-collections.chuyen-thang');
    Route::post('/xoa/{debtCollection}', [DebtCollectionController::class, 'destroy'])->name('debt-collections.destroy');
    Route::get('/export', [DebtCollectionController::class, 'export'])->name('debt-collections.export');
    Route::post('/import', [DebtCollectionController::class, 'import'])->name('debt-collections.import');
    Route::get('/template', [DebtCollectionController::class, 'downloadTemplate'])->name('debt-collections.template');
});
