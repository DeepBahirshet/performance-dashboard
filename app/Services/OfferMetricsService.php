<?php

namespace App\Services;

use App\Models\Offer;
use App\Repositories\OfferAnalyticsRepository;
use Carbon\Carbon;

class OfferMetricsService
{
    protected OfferAnalyticsRepository $repo;
    protected ForecastService $forecastService;

    public function __construct(OfferAnalyticsRepository $repo, ForecastService $forecastService)
    {
        $this->repo = $repo;
        $this->forecastService = $forecastService;
    }

    public function dashboardMetrics(Offer $offer, int $days = 30, int $forecastDays = 14): array
    {
        $to = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays($days - 1)->toDateString(); // last $days days

        // repo returns array of ['date' => 'YYYY-MM-DD', 'count' => int]
        $daily = $this->repo->dailyRedemptions($offer);
        $dailyCounts = array_map(fn($r) => (int) ($r['count'] ?? 0), $daily);

        $cumulative = $this->repo->cumulativeFromDaily($daily);
        $kpis = $this->repo->kpis($offer, $offer->start_date, $to);

        // Forecast
        $forecastSeries = [];
        if (!empty($dailyCounts) && array_sum($dailyCounts) > 0) {
            $forecastSeries = $this->forecastService->movingAverageForecast($dailyCounts, 7, $forecastDays);
        } else {
            // fallback: zeros
            $forecastSeries = array_fill(0, $forecastDays, 0);
        }

        $labels = array_map(fn($r) => $r['date'], $daily);

        $forecastLabels = [];
        for ($i = 1; $i <= $forecastDays; $i++) {
            $forecastLabels[] = Carbon::parse($to)->addDays($i)->toDateString();
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
