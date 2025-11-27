<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use Illuminate\Support\Carbon;

class OfferSeeder extends Seeder
{
    public function run()
    {
        Offer::create([
            'name'           => 'New Year Mega Deal',
            'offer_code'     => 'NEWYEAR2025',   
            'start_date'     => Carbon::now()->subDays(10),
            'end_date'       => Carbon::now()->addDays(10),
            'budget'         => 5000,
            'discount_value' => 10,
            'discount_type'  => '%',   
            'max_redemptions' => 4,
        ]);

        Offer::create([
            'name'           => 'Festival Bumper Offer',
            'offer_code'     => 'BLACKFRIDAY10', 
            'start_date'     => Carbon::now()->subDays(5),
            'end_date'       => Carbon::now()->addDays(20),
            'budget'         => 10000,
            'discount_value' => 20,
            'discount_type'  => 'flat', 
            'max_redemptions' => 10,  
        ]);

        Offer::create([
            'name'           => 'Flash Sale',
            'offer_code'     => 'SALE20', 
            'start_date'     => Carbon::now()->subDays(1),
            'end_date'       => Carbon::now()->addDays(2),
            'budget'         => 3000,
            'discount_value' => 15,
            'discount_type'  => '%', 
            'max_redemptions' => 3,
        ]);
    }
}
