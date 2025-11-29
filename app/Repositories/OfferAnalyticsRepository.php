<?php
namespace App\Repositories;

use App\Models\Offer;
use App\Models\Redemption;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OfferAnalyticsRepository
{
    public function dailyRedemptions(Offer $offer): array
    {
        $from = $from ?? $offer->start_date->toDateString();
        $to = min($offer->end_date, Carbon::now()->toDateString());

        $rows = DB::select(
            "
            SELECT gs.day::date as date,
                   COALESCE(r.cnt, 0) as count
            FROM generate_series(:from::date, :to::date, interval '1 day') as gs(day)
            LEFT JOIN (
                SELECT redemption_date::date as d, COUNT(*) as cnt
                FROM redemptions
                WHERE offer_id = :offer_id AND redemption_date BETWEEN :from AND :to
                GROUP BY redemption_date::date
            ) r ON r.d = gs.day::date
            ORDER BY gs.day::date
            ",
            ['from' => $from, 'to' => $to, 'offer_id' => $offer->id]
        );

        return array_map(fn($r) => ['date' => (string)$r->date, 'count' => (int)$r->count], $rows);
    }

    public function cumulativeFromDaily(array $dailySeries): array
    {
        $running = 0;
        $out = [];
        foreach ($dailySeries as $row) {
            $running += $row['count'];
            $out[] = ['date' => $row['date'], 'cumulative' => $running];
        }
        return $out;
    }

    public function kpis(Offer $offer): array
    {
        $from = $from ?? $offer->start_date->toDateString();
        $to = min($offer->end_date, Carbon::now()->toDateString());

        $totalRedemptions = (int) $offer->redemptions()->whereBetween('redemption_date', [$from, $to])->count();
        $totalDiscount = (float) $offer->redemptions()->whereBetween('redemption_date', [$from, $to])->sum('discount_given');
        $remainingBudget = max(0, (float)$offer->budget - $totalDiscount);

        $redemptionRate = $offer->max_redemptions
            ? round(($totalRedemptions / $offer->max_redemptions) * 100, 2)
            : null;

        $periodDays = (new \DateTime($from))->diff(new \DateTime($to))->days + 1;
        $avgDaily = $periodDays ? round($totalRedemptions / $periodDays, 2) : 0;

        return [
            'total_redemptions' => $totalRedemptions,
            'total_discount' => round($totalDiscount, 2),
            'remaining_budget' => round($remainingBudget, 2),
            'redemption_rate_percent' => $redemptionRate,
            'avg_daily_redemption' => $avgDaily,
        ];
    }

    public function totalSpent(Offer $offer, string $from, string $to): float
    {
        return (float) Redemption::where('offer_id', $offer->id)
            ->whereBetween('redemption_date', [$from, $to])
            ->sum('discount_given');
    }


}