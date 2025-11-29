<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
   dispatch(new App\Jobs\ComputeOfferForecast(1, 'moving_average', 7, 14));
    
})
->name('compute-offer-forecast')
->dailyAt('00:10')
->withoutOverlapping()
->onOneServer();
