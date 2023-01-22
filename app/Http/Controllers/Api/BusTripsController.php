<?php

namespace App\Http\Controllers\Api;

use App\Models\Bus;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripCollection;

class BusTripsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Bus $bus)
    {
        $this->authorize('view', $bus);

        $search = $request->get('search', '');

        $trips = $bus
            ->trips()
            ->search($search)
            ->latest()
            ->paginate();

        return new TripCollection($trips);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Bus $bus)
    {
        $this->authorize('create', Trip::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'frequent' => ['nullable', 'max:255', 'string'],
            'pickup_id' => ['required', 'exists:stations,id'],
            'destination_id' => ['required', 'exists:stations,id'],
            'type' => ['required', 'in:short,long'],
            'distance' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'start_at' => ['nullable', 'date_format:H:i:s'],
            'end_at' => ['nullable', 'date_format:H:i:s'],
            'cron_experations' => ['nullable', 'max:255', 'string'],
            'active' => ['required', 'boolean'],
        ]);

        $trip = $bus->trips()->create($validated);

        return new TripResource($trip);
    }
}
