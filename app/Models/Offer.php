<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'offer_code',
        'start_date',
        'end_date',
        'budget',
        'max_redemptions',
        'discount_type',
        'discount_value',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'discount_value' => 'decimal:2',
    ];

    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    public function totalDiscountGiven()
    {
        return $this->redemptions()->sum('discount_given');
    }

    public function totalRedemptions()
    {
        return $this->redemptions()->count();
    }

}
