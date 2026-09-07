<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/responses', [AdminResponseController::class, 'index'])->name('responses.index');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
});

require __DIR__.'/survey.php';