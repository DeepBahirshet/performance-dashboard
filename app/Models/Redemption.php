<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'customer_id',
        'redemption_date',
        'order_amount',
        'discount_given',
    ];

    protected $casts = [
        'redemption_date' => 'date',
        'order_amount' => 'decimal:2',
        'discount_given' => 'decimal:2',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
