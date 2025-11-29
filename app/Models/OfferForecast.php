<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferForecast extends Model
{
    protected $fillable = ['offer_id','method','series','window','generated_at'];

    protected $casts = [
        'series' => 'array',
        'generated_at' => 'datetime',
    ];
    
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}