<?php

use App\Http\Controllers\Api\OutlookWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/outlook/webhook', OutlookWebhookController::class)
    ->name('api.outlook.webhook');
