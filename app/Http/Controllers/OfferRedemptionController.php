<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedemptionRequest;
use App\Models\Offer;
use App\Services\OfferService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferRedemptionController extends Controller
{
    protected OfferService $service;

    public function __construct(OfferService $service){
        $this->service = $service;
    }

    public function redeem(RedemptionRequest $request, Offer $offer): JsonResponse
    {
        $payload = $request->validated();

        $orderAmount = (float) $payload['order_amount'];

        // percent
        if ($offer->discount_type === '%') {
            $discountGiven = round($orderAmount * ((float)$offer->discount_value) / 100, 2);
        } else 

        { // 'flat'
            $discountGiven = round(min((float)$offer->discount_value, $orderAmount), 2);
        }

        // don't let discount exceed order amount
        $discountGiven = min($discountGiven, $orderAmount);

        // redemption_date
        $payload['redemption_date'] = Carbon::now()->toDateString();
        
        $payload['discount_given'] = $discountGiven;

        try {
            $result = $this->service->redeem($offer, $payload);

            return response()->json([
                'status' => true,
                'message' => $result['message'],
                'data' => $result['data'] ?? [],
            ], $result['code'] ?? 200);

        } catch (\RuntimeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            // log($e)
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
