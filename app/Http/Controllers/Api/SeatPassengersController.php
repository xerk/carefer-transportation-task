<?php

namespace App\Http\Controllers\Api;

use App\Models\Seat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PassengerResource;
use App\Http\Resources\PassengerCollection;

class SeatPassengersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Seat $seat)
    {
        $this->authorize('view', $seat);

        $search = $request->get('search', '');

        $passengers = $seat
            ->passengers()
            ->search($search)
            ->latest()
            ->paginate();

        return new PassengerCollection($passengers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Seat $seat)
    {
        $this->authorize('create', Passenger::class);

        $validated = $request->validate([
            'type' => ['required', 'in:guest,user'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $passenger = $seat->passengers()->create($validated);

        return new PassengerResource($passenger);
    }
}
