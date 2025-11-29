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
            'max_redemptions'=> 4,
        ]);

        Offer::create([
            'name'           => 'Festival Bumper Offer',
            'offer_code'     => 'BLACKFRIDAY10',
            'start_date'     => Carbon::now()->subDays(5),
            'end_date'       => Carbon::now()->addDays(20),
            'budget'         => 10000,
            'discount_value' => 20,
            'discount_type'  => 'flat',
            'max_redemptions'=> 10,
        ]);

        Offer::create([
            'name'           => 'Flash Sale',
            'offer_code'     => 'SALE20',
            'start_date'     => Carbon::now()->subDays(1),
            'end_date'       => Carbon::now()->addDays(2),
            'budget'         => 3000,
            'discount_value' => 15,
            'discount_type'  => '%',
            'max_redemptions'=> 3,
        ]);

        Offer::create([
            'name'           => 'Summer Saver Deal',
            'offer_code'     => 'SUMMER25',
            'start_date'     => Carbon::now()->subDays(20),
            'end_date'       => Carbon::now()->addDays(5),
            'budget'         => 12000,
            'discount_value' => 25,
            'discount_type'  => '%',
            'max_redemptions'=> 15,
        ]);

        Offer::create([
            'name'           => 'Monsoon Madness',
            'offer_code'     => 'RAIN15',
            'start_date'     => Carbon::now()->subDays(30),
            'end_date'       => Carbon::now()->addDays(1),
            'budget'         => 7000,
            'discount_value' => 15,
            'discount_type'  => 'flat',
            'max_redemptions'=> 6,
        ]);

        Offer::create([
            'name'           => 'Diwali Dhamaka',
            'offer_code'     => 'LIGHTS50',
            'start_date'     => Carbon::now()->subDays(40),
            'end_date'       => Carbon::now()->subDays(10),
            'budget'         => 25000,
            'discount_value' => 50,
            'discount_type'  => '%',
            'max_redemptions'=> 30,
        ]);

        Offer::create([
            'name'           => 'Weekend Exclusive',
            'offer_code'     => 'WEEKEND5',
            'start_date'     => Carbon::now()->subDays(2),
            'end_date'       => Carbon::now()->addDays(1),
            'budget'         => 2000,
            'discount_value' => 5,
            'discount_type'  => '%',
            'max_redemptions'=> 2,
        ]);

        Offer::create([
            'name'           => 'End of Season Blowout',
            'offer_code'     => 'SEASON40',
            'start_date'     => Carbon::now()->subDays(15),
            'end_date'       => Carbon::now()->addDays(12),
            'budget'         => 18000,
            'discount_value' => 40,
            'discount_type'  => '%',
            'max_redemptions'=> 20,
        ]);

        Offer::create([
            'name'           => 'Clearance Sale',
            'offer_code'     => 'CLEAR99',
            'start_date'     => Carbon::now()->subDays(50),
            'end_date'       => Carbon::now()->addDays(3),
            'budget'         => 50000,
            'discount_value' => 99,
            'discount_type'  => 'flat',
            'max_redemptions'=> 50,
        ]);

        Offer::create([
            'name'           => 'VIP Loyalty Bonus',
            'offer_code'     => 'LOYALTY30',
            'start_date'     => Carbon::now()->subDays(60),
            'end_date'       => Carbon::now()->addDays(30),
            'budget'         => 15000,
            'discount_value' => 30,
            'discount_type'  => '%',
            'max_redemptions'=> 25,
        ]);

        Offer::create([
            'name'           => 'Winter Warmup Offer',
            'offer_code'     => 'WINTER18',
            'start_date'     => Carbon::now()->subDays(12),
            'end_date'       => Carbon::now()->addDays(22),
            'budget'         => 9000,
            'discount_value' => 18,
            'discount_type'  => '%',
            'max_redemptions'=> 12,
        ]);

        Offer::create([
            'name'           => 'App-Only Special Deal',
            'offer_code'     => 'APPONLY30',
            'start_date'     => Carbon::now()->subDays(3),
            'end_date'       => Carbon::now()->addDays(25),
            'budget'         => 8000,
            'discount_value' => 30,
            'discount_type'  => '%',
            'max_redemptions'=> 8,
        ]);

        Offer::create([
            'name'           => 'Influencer Mega Collab',
            'offer_code'     => 'INFLU20',
            'start_date'     => Carbon::now()->subDays(7),
            'end_date'       => Carbon::now()->addDays(40),
            'budget'         => 20000,
            'discount_value' => 20,
            'discount_type'  => 'flat',
            'max_redemptions'=> 40,
        ]);

        Offer::create([
            'name'           => 'Anniversary Celebration Deal',
            'offer_code'     => 'ANNI45',
            'start_date'     => Carbon::now()->subDays(25),
            'end_date'       => Carbon::now()->addDays(5),
            'budget'         => 15000,
            'discount_value' => 45,
            'discount_type'  => '%',
            'max_redemptions'=> 18,
        ]);

        Offer::create([
            'name'           => 'Inventory Liquidation Deal',
            'offer_code'     => 'CLEAR40',
            'start_date'     => Carbon::now()->subDays(60),
            'end_date'       => Carbon::now()->addDays(15),
            'budget'         => 30000,
            'discount_value' => 40,
            'discount_type'  => 'flat',
            'max_redemptions'=> 60,
        ]);

        Offer::create([
            'name'           => 'Limited-Time Mystery Discount',
            'offer_code'     => 'MYSTERY?',
            'start_date'     => Carbon::now()->subDays(2),
            'end_date'       => Carbon::now()->addDays(1),
            'budget'         => 4000,
            'discount_value' => 12,
            'discount_type'  => '%',
            'max_redemptions'=> 4,
        ]);

        Offer::create([
            'name'           => 'Premium Customers Exclusive',
            'offer_code'     => 'PREM55',
            'start_date'     => Carbon::now()->subDays(15),
            'end_date'       => Carbon::now()->addDays(35),
            'budget'         => 50000,
            'discount_value' => 55,
            'discount_type'  => '%',
            'max_redemptions'=> 30,
        ]);

        Offer::create([
            'name'           => 'Mega Combo Deal',
            'offer_code'     => 'COMBO22',
            'start_date'     => Carbon::now()->subDays(5),
            'end_date'       => Carbon::now()->addDays(18),
            'budget'         => 11000,
            'discount_value' => 22,
            'discount_type'  => '%',
            'max_redemptions'=> 15,
        ]);

        Offer::create([
            'name'           => 'Refer & Earn Special',
            'offer_code'     => 'REFER10',
            'start_date'     => Carbon::now()->subDays(14),
            'end_date'       => Carbon::now()->addDays(14),
            'budget'         => 6000,
            'discount_value' => 10,
            'discount_type'  => 'flat',
            'max_redemptions'=> 10,
        ]);

        Offer::create([
            'name'           => 'Early Bird Super Saver',
            'offer_code'     => 'EARLY70',
            'start_date'     => Carbon::now()->subDays(9),
            'end_date'       => Carbon::now()->addDays(3),
            'budget'         => 22000,
            'discount_value' => 70,
            'discount_type'  => '%',
            'max_redemptions'=> 20,
        ]);

    }
    

}
