<?php

namespace App\Http\Controllers\Api;

use App\Models\Station;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripCollection;

class StationTripsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Station $station
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Station $station)
    {
        $this->authorize('view', $station);

        $search = $request->get('search', '');

        $trips = $station
            ->pickups()
            ->search($search)
            ->latest()
            ->paginate();

        return new TripCollection($trips);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Station $station
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Station $station)
    {
        $this->authorize('create', Trip::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'frequent' => ['nullable', 'max:255', 'string'],
            'bus_id' => ['required', 'exists:buses,id'],
            'type' => ['required', 'in:short,long'],
            'distance' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'start_at' => ['nullable', 'date_format:H:i:s'],
            'end_at' => ['nullable', 'date_format:H:i:s'],
            'cron_experations' => ['nullable', 'max:255', 'string'],
            'active' => ['required', 'boolean'],
        ]);

        $trip = $station->pickups()->create($validated);

        return new TripResource($trip);
    }
}
