<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OfferController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('offers', OfferController::class);
});