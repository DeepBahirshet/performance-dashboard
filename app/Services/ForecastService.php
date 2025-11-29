<?php
namespace App\Services;

class ForecastService
{
    public function movingAverageForecast(array $series, int $window = 7, int $days = 14): array
    {
        $forecast = [];
        $data = $series;
        for ($d = 0; $d < $days; $d++) {
            $slice = array_slice($data, -$window);
            $avg = $slice ? (array_sum($slice) / count($slice)) : 0;
            $forecast[] = round($avg, 2);
            $data[] = $avg;
        }
        return $forecast;
    }
}