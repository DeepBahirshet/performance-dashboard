<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OfferDashboardController;

Route::name('admin.')->group(function () {
    Route::resource('offers', OfferController::class);
    Route::get('offers/{offer}/dashboard', [OfferDashboardController::class, 'show'])->name('offers.dashboard');
});
