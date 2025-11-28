<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Repositories\OfferAnalyticsRepository;
use App\Services\ForecastService;
use Illuminate\Http\Request;

class OfferDashboardController extends Controller
{
    public function __construct(
        protected OfferAnalyticsRepository $repo,
        protected ForecastService $forecastService
    ) {}

    public function show(Offer $offer)
    {
        // date range - last 30 days
        $to = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays(29)->toDateString();

        $daily = $this->repo->dailyRedemptions($offer, $from, $to); // array of {date,count}
        $dailyCounts = array_map(fn($r) => $r['count'], $daily);
        $cumulative = $this->repo->cumulativeFromDaily($daily);
        $kpis = $this->repo->kpis($offer, $from, $to);

        $forecastDays = 14;
        // moving average with window = 7
        $forecastSeries = $this->forecastService->movingAverageForecast($dailyCounts, 7, $forecastDays);

        $labels = array_map(fn($r) => $r['date'], $daily);
        
        $forecastLabels = [];
        for ($i = 1; $i <= $forecastDays; $i++) {
            $forecastLabels[] = Carbon::parse($to)->addDays($i)->toDateString();
        }

        return Inertia::render('Admin/Offers/Dashboard', [
            'offer' => $offer,
            'labels' => $labels,
            'dailyCounts' => $dailyCounts,
            'cumulative' => $cumulative,
            'kpis' => $kpis,
            'forecast' => $forecastSeries,
            'forecastLabels' => $forecastLabels,
            'budget' => (float)$offer->budget,
        ]);
    }
}
