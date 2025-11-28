<?php

use App\Http\Controllers\OfferRedemptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/offers/{offer}/redeem', [OfferRedemptionController::class, 'redeem'])->name('offers.redeem');
