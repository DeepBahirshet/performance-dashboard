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

    /**
     * Simple linear regression using day index; returns next $days predictions.
     */
    public function linearRegressionForecast(array $series, int $days = 14): array
    {
        $n = count($series);
        if ($n === 0) return array_fill(0, $days, 0);

        $x = range(0, $n - 1);
        $y = $series;
        $x_mean = array_sum($x) / $n;
        $y_mean = array_sum($y) / $n;

        $num = 0; $den = 0;
        for ($i = 0; $i < $n; $i++) {
            $num += ($x[$i] - $x_mean) * ($y[$i] - $y_mean);
            $den += pow($x[$i] - $x_mean, 2);
        }
        $b = $den == 0 ? 0 : $num / $den;
        $a = $y_mean - $b * $x_mean;

        $forecast = [];
        for ($i = 0; $i < $days; $i++) {
            $xi = $n + $i;
            $forecast[] = round($a + $b * $xi, 2);
        }
        return $forecast;
    }
}