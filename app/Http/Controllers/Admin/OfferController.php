<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected OfferService $service;

    public function __construct(OfferService $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $filters = $request->only('q');
        $offers = $this->service->paginateForAdmin($filters, 15);

        return Inertia::render('Admin/Offers/Index', [
            'offers' => $offers,
            'filters' => $filters,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Offers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferRequest $request)
    {
        $data = $request->validated();
        $this->service->create($data);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return Inertia::render('Admin/Offers/Show', [
            'offer' => $offer->loadCount('redemptions'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
    return Inertia::render('Admin/Offers/Edit', [
        'offer' => $offer,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        $data = $request->validated();
        $this->service->update($offer, $data);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $this->service->delete($offer);
        return back(303)->with('success', 'Offer deleted.');
    }
}
