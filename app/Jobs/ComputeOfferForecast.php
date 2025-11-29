<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Models\OfferForecast;
use App\Repositories\OfferAnalyticsRepository;
use App\Services\ForecastService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ComputeOfferForecast implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public ?int $offerId;
    public string $method;
    public int $window;
    public int $days;

    /**
     * $offerId if null then job should be  used for all offers.
     */
    public function __construct(?int $offerId = null, string $method = 'moving_average', int $window = 7, int $days = 14)
    {
        $this->offerId = $offerId;
        $this->method = $method;
        $this->window = $window;
        $this->days = $days;
    }

    /**
     * Execute the job.
     */
    public function handle(ForecastService $forecastService, OfferAnalyticsRepository $repo): void
    {
        $to = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays(29)->toDateString();

        $offers = $this->offerId ? Offer::where('id', $this->offerId)->get() : Offer::all();

        foreach($offers as $offer){
            // daily date and count for last 30 days
            $daily = $repo->dailyRedemptions($offer);
            $counts = array_map(fn($r) => $r['count'], $daily);

            // if zero counts store zeros and no forecasting
            if(empty($counts) || array_sum($counts) == 0){
                $forecastSeries = array_fill(0, $this->days, 0);
            }
            else{
                $forecastSeries = $forecastService->movingAverageForecast($counts, $this->window, $this->days);
            }

            // upsert forecast
            OfferForecast::updateOrCreate(
                ['offer_id' => $offer->id, 'method' => $this->method],
                ['series' => $forecastSeries, 'window' => $this->window, 'generated_at' => now()]
            );
        }
    }
}
