<?php
namespace App\Repositories;

use App\Models\Offer;
use App\Models\OfferForecast;
use App\Models\Redemption;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OfferForecastRepository
{
     public function getForOffer(int $offerId, string $method = 'moving_average')
    {

        return OfferForecast::where('offer_id', $offerId)
        ->where('method', $method)
        ->first();
    }

    public function storeForOffer(int $offerId, string $method, array $series, array $meta = [])
    {
        $attrs = array_merge([
            'series' => $series,
            'window' => $meta['window'] ?? null,
            'generated_at' => $meta['generated_at'] ?? now(),
        ], $meta['extra'] ?? []);

        return OfferForecast::updateOrCreate(
            ['offer_id' => $offerId, 'method' => $method],
            $attrs
        );
    }

    public function isStale(int $offerId, string $method, int $ttlHours = 24): bool
    {
        $rec = $this->getForOffer($offerId, $method);
        if (! $rec || ! $rec->generated_at) return true;
        return $rec->generated_at->lt(now()->subHours($ttlHours));
    }

}