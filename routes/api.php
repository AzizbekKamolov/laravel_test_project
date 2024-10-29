<?php

use App\Http\Controllers\Api\{CategoryController, ProviderController};
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'getOne']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);

});
Route::prefix('providers')->name('providers.')->group(function () {
    Route::get('/', [ProviderController::class, 'index'])->name('index');
    Route::post('/store', [ProviderController::class, 'store'])->name('store');
    Route::get('/{id}', [ProviderController::class, 'getOne'])->name('getOne');
    Route::put('/{id}', [ProviderController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProviderController::class, 'destroy'])->name('destroy');

});
