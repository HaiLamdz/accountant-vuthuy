<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebtCollectionController;

Route::get('/', [DebtCollectionController::class, 'index'])->name('debt-collections.index');
Route::get('/them-moi', [DebtCollectionController::class, 'create'])->name('debt-collections.create');
Route::post('/them-moi', [DebtCollectionController::class, 'store'])->name('debt-collections.store');
Route::post('/thu/{debtCollection}', [DebtCollectionController::class, 'thu'])->name('debt-collections.thu');
Route::post('/chuyen-thang/{debtCollection}', [DebtCollectionController::class, 'chuyenThang'])->name('debt-collections.chuyen-thang');
Route::post('/xoa/{debtCollection}', [DebtCollectionController::class, 'destroy'])->name('debt-collections.destroy');
Route::get('/export', [DebtCollectionController::class, 'export'])->name('debt-collections.export');
Route::post('/import', [DebtCollectionController::class, 'import'])->name('debt-collections.import');
Route::get('/template', [DebtCollectionController::class, 'downloadTemplate'])->name('debt-collections.template');
