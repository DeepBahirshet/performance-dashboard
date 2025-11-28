<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

     public function redeem(Offer $offer, array $data): array
    {
        $data['redemption_date'] = $data['redemption_date'] ?? Carbon::now()->toDateString();
        $discountAmount = (float) ($data['discount_given'] ?? 0);

        return DB::transaction(function () use ($offer, $data, $discountAmount) {
            // lock the offer
            $lockedOffer = Offer::where('id', $offer->id)->lockForUpdate()->first();

            // ensure date checks
            $date = Carbon::parse($data['redemption_date'])->toDateString();
            if ($lockedOffer->start_date && $date < $lockedOffer->start_date->toDateString()) {
                throw new \RuntimeException('Offer not started yet.');
            }
            if ($lockedOffer->end_date && $date > $lockedOffer->end_date->toDateString()) {
                throw new \RuntimeException('Offer expired.');
            }

            // compute totals under lock
            $totalRedemptions = (int) $lockedOffer->redemptions()->count();
            $totalDiscountGiven = (float) $lockedOffer->redemptions()->sum('discount_given');
            $remainingBudget = max(0, (float)$lockedOffer->budget - $totalDiscountGiven);

            // max redemptions check
            if (!is_null($lockedOffer->max_redemptions) && $totalRedemptions >= $lockedOffer->max_redemptions) {
                throw new \RuntimeException('Offer fully redeemed.');
            }

            // budget check: discount must be <= remaining budget
            if ($discountAmount > $remainingBudget) {
                throw new \RuntimeException('Insufficient budget.');
            }

            // create redemption
            $redemption = $lockedOffer->redemptions()->create([
                'customer_id'     => $data['customer_id'] ?? null,
                'redemption_date' => $data['redemption_date'],
                'order_amount'    => $data['order_amount'],
                'discount_given'  => $discountAmount,
            ]);

            return [
                'status' => true,
                'code' => 201,
                'message' => 'Redeemed',
                'data' => [
                    'redemption_id' => $redemption->id,
                    'offer_id' => $lockedOffer->id,
                    'remaining_budget' => round($remainingBudget - $discountAmount, 2),
                    'total_redemptions' => $totalRedemptions + 1,
                    'discount_given' => round($discountAmount, 2),
                ],
            ];
        }, 3);
    }
}
