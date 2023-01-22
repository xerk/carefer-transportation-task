<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PassengerResource;
use App\Http\Resources\PassengerCollection;

class OrderPassengersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $search = $request->get('search', '');

        $passengers = $order
            ->passengers()
            ->search($search)
            ->latest()
            ->paginate();

        return new PassengerCollection($passengers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Order $order)
    {
        $this->authorize('create', Passenger::class);

        $validated = $request->validate([
            'type' => ['required', 'in:guest,user'],
            'user_id' => ['nullable', 'exists:users,id'],
            'seat_id' => ['required', 'exists:seats,id'],
        ]);

        $passenger = $order->passengers()->create($validated);

        return new PassengerResource($passenger);
    }
}
