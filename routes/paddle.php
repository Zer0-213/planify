<?php

Route::post(
    'webhook-url',
    '\Laravel\Paddle\Http\Controllers\WebhookController@handleWebhook'
);
