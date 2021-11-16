<?php

use Rias\StatamicLemonSqueezy\Http\Controllers\LemonSqueezyController;
use Rias\StatamicLemonSqueezy\Http\Controllers\ProductsController;
use Rias\StatamicLemonSqueezy\Http\Controllers\StoresController;


Route::prefix('lemon-squeezy')->group(function () {
    Route::get('/', [LemonSqueezyController::class, 'index'])->name('lemon-squeezy');

    Route::post('oauth', [LemonSqueezyController::class, 'oauth'])->name('lemon-squeezy.oauth');
    Route::delete('oauth', [LemonSqueezyController::class, 'disconnect'])->name('lemon-squeezy.oauth');
    Route::get('oauth/callback', [LemonSqueezyController::class, 'callback'])->name('lemon-squeezy.callback');

    Route::prefix('api')->group(function () {
        Route::get('stores', '\\'.StoresController::class);
        Route::get('products', '\\'.ProductsController::class);
    });
});
