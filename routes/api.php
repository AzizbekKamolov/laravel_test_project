<?php

use App\Http\Controllers\Api\{CategoryController,
    ClientController,
    ProductController,
    ProviderController,
    StorageController
};
use Illuminate\Support\Facades\Route;


Route::post('buying-products', [StorageController::class, 'buyingProducts']);
Route::post('refund', [StorageController::class, 'refund']);
Route::get('get-products', [StorageController::class, 'getProducts']);
Route::get('make-order', [StorageController::class, 'makeOrder']);


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
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}', [ProductController::class, 'getOne'])->name('getOne');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');

});
Route::prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('index');
    Route::post('/store', [ClientController::class, 'store'])->name('store');
    Route::get('/{id}', [ClientController::class, 'getOne'])->name('getOne');
    Route::put('/{id}', [ClientController::class, 'update'])->name('update');
    Route::delete('/{id}', [ClientController::class, 'destroy'])->name('destroy');

});
