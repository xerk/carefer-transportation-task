<?php

namespace App\Http\Controllers\Api;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class TripOrdersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Trip $trip)
    {
        $this->authorize('view', $trip);

        $search = $request->get('search', '');

        $orders = $trip
            ->orders()
            ->search($search)
            ->latest()
            ->paginate();

        return new OrderCollection($orders);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Trip $trip)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'name' => ['nullable', 'max:255', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'max:255', 'string'],
            'discount_id' => ['nullable', 'exists:discounts,id'],
            'payment_type' => ['required', 'in:cash,card'],
            'tax' => ['nullable', 'max:255', 'string'],
            'subtotal_amount' => ['nullable', 'max:255', 'string'],
            'total_amount' => ['nullable', 'max:255', 'string'],
        ]);

        $order = $trip->orders()->create($validated);

        return new OrderResource($order);
    }
}
