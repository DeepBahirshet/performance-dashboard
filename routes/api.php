<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferRedemptionController;

Route::post('/offers/{offer}/redeem', [OfferRedemptionController::class, 'redeem'])
    ->name('api.offers.redeem');
