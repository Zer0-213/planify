<?php


use Laravel\Paddle\Http\Controllers\WebhookController;

Route::post(
    'paddle/webhook', WebhookController::class,
);
