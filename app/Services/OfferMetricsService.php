<?php

namespace App\Services;

use App\Jobs\ComputeOfferForecast;
use App\Models\Offer;
use App\Repositories\OfferAnalyticsRepository;
use App\Repositories\OfferForecastRepository;
use Carbon\Carbon;

class OfferMetricsService
{
    protected OfferAnalyticsRepository $analyticsRepo;

    protected OfferForecastRepository $forecastRepo;
    protected ForecastService $forecastService;

    public function __construct(OfferAnalyticsRepository $analyticsRepo, OfferForecastRepository $forecastRepo, ForecastService $forecastService)
    {
        $this->repo = $analyticsRepo;
        $this->forecastRepo = $forecastRepo;
        $this->forecastService = $forecastService;
    }

    public function dashboardMetrics(Offer $offer, int $days = 30, int $forecastDays = 14)
    {
        $to = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays($days - 1)->toDateString(); // last $days days

        // repo returns array of ['date' => 'YYYY-MM-DD', 'count' => int]
        $daily = $this->repo->dailyRedemptions($offer);
        $dailyCounts = array_map(fn($r) => (int) ($r['count'] ?? 0), $daily);

        $cumulative = $this->repo->cumulativeFromDaily($daily);
        $kpis = $this->repo->kpis($offer);

        // Forecast
        // $forecastSeries = [];
        // if (!empty($dailyCounts) && array_sum($dailyCounts) > 0) {
        //     $forecastSeries = $this->forecastService->movingAverageForecast($dailyCounts, 7, $forecastDays);
        // } else {
        //     // fallback: zeros
        //     $forecastSeries = array_fill(0, $forecastDays, 0);
        // }

        $labels = array_map(fn($r) => $r['date'], $daily);

        $forecastLabels = [];
        for ($i = 1; $i <= $forecastDays; $i++) {
            $forecastLabels[] = Carbon::parse($to)->addDays($i)->toDateString();
        }

        $forecastRec = $this->forecastRepo->getForOffer($offer->id, 'moving_average');

        $useCached = ($forecastRec && !empty($forecastRec->series)) && (!$this->forecastRepo->isStale($offer->id, 'moving_average', 6));

        if ($useCached) {
            $forecastSeries = (array) $forecastRec->series;
            $forecastLabels = (array) $forecastLabels;
            $generatedAt = $forecastRec->generated_at;
        }

        else {
            // dispatch background compute
            ComputeOfferForecast::dispatch($offer->id, 'moving_average', 7, $forecastDays)->delay(now()->addSeconds(5));
            // compute or zeros
            $forecastSeries = (!empty($dailyCounts) && array_sum($dailyCounts) > 0)
                ? $this->forecastService->movingAverageForecast($dailyCounts, 7, $forecastDays)
                : array_fill(0, $forecastDays, 0);

            $generatedAt = null;
        }

        $budgetUtilization = $this->computeBudgetUtilization($offer, $offer->start_date, $to);

        return [
            'range' => ['from' => $from, 'to' => $to],
            'daily' => [
                'labels' => $labels,
                'counts' => $dailyCounts,
            ],
            'cumulative' => $cumulative,
            'kpis' => $kpis,
            'forecast' => [
                'labels' => $forecastLabels,
                'series' => $forecastSeries,
            ],
            'budget_utilization' => $budgetUtilization,
            'generatedAt' => $generatedAt
        ];
    }

    public function computeBudgetUtilization(Offer $offer, string $from, string $to): array
    {
        $spent = $this->repo->totalSpent($offer, $from, $to);

        $budget = (float) $offer->budget;
        $budgetRemaining = max(0, $budget - $spent);
        $budgetPct = $budget > 0 ? round(($spent / $budget) * 100, 2) : 0;

        return [
            'spent' => $spent,
            'budgetRemaining' => $budgetRemaining,
            'budgetPct' => $budgetPct,
        ];
    }
}
