<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Services\OfferMetricsService;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Repositories\OfferAnalyticsRepository;
use App\Services\ForecastService;
use Illuminate\Http\Request;

class OfferDashboardController extends Controller
{
    public function show(Offer $offer, OfferMetricsService $offerMetricsService)
    {
        
        $data = $offerMetricsService->dashboardMetrics($offer);


        return Inertia::render('Admin/Offers/Dashboard', [
            'offer' => $offer,
           'metrics' => $data
        ]);
    }
}
