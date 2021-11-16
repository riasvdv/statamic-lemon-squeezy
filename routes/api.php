<?php

use Rias\StatamicLemonSqueezy\Http\Controllers\Api\LemonSqueezyWebhookController;

Route::middleware('api')->group(function () {
    Route::post('lemon-squeezy/webhook', '\\' . LemonSqueezyWebhookController::class)->name('lemon-squeezy.webhook');
});
