<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class OfferService
{
    // get paginated list for admin with simple filters
    public function paginateForAdmin(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Offer::query()->orderBy('created_at', 'desc');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function($qb) use ($q) {
                $qb->where('name', 'ilike', "%{$q}%") // case insensitive
                   ->orWhere('offer_code', 'ilike', "%{$q}%"); // case insensitive
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    public function update(Offer $offer, array $data): Offer
    {
        $offer->update($data);
        return $offer;
    }

    public function delete(Offer $offer): bool
    {
        return $offer->delete();
    }
}
