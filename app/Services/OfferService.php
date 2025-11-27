<?php

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    public function createOffer(array $data)
    {
        return Offer::create($data);
    }

    public function updateOffer(Offer $offer, array $data)
    {
        return $offer->update($data);
    }

    public function getActiveOffers()
    {
        return Offer::where('is_active', true)->get();
    }

    public function deleteOffer(Offer $offer)
    {
        return $offer->delete();
    }
}
