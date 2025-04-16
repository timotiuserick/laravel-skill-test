<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('product.index');
Route::get('/products', [HomeController::class, 'fetch'])->name('product.fetch');
Route::post('/products', [HomeController::class, 'store'])->name('product.store');
Route::put('/products', [HomeController::class, 'update'])->name('product.update');
Route::delete('/products', [HomeController::class, 'delete'])->name('product.delete');
