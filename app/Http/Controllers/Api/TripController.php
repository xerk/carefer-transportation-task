<?php

namespace App\Http\Controllers\Api;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Resources\TripResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripCollection;

class TripController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Trip::class);

        $search = $request->get('search', '');

        $trips = Trip::search($search)
            ->latest()
            ->paginate();

        return new TripCollection($trips);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Trip::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'frequent' => ['nullable', 'max:255', 'string'],
            'pickup_id' => ['required', 'exists:stations,id'],
            'destination_id' => ['required', 'exists:stations,id'],
            'bus_id' => ['required', 'exists:buses,id'],
            'type' => ['required', 'in:short,long'],
            'distance' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'start_at' => ['nullable', 'date_format:H:i:s'],
            'end_at' => ['nullable', 'date_format:H:i:s'],
            'cron_experations' => ['nullable', 'max:255', 'string'],
            'active' => ['required', 'boolean'],
        ]);

        $trip = Trip::create($validated);

        return new TripResource($trip);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Trip $trip)
    {
        $this->authorize('view', $trip);

        return new TripResource($trip);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'frequent' => ['nullable', 'max:255', 'string'],
            'pickup_id' => ['required', 'exists:stations,id'],
            'destination_id' => ['required', 'exists:stations,id'],
            'bus_id' => ['required', 'exists:buses,id'],
            'type' => ['required', 'in:short,long'],
            'distance' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'start_at' => ['nullable', 'date_format:H:i:s'],
            'end_at' => ['nullable', 'date_format:H:i:s'],
            'cron_experations' => ['nullable', 'max:255', 'string'],
            'active' => ['required', 'boolean'],
        ]);

        $trip->update($validated);

        return new TripResource($trip);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Trip $trip)
    {
        $this->authorize('delete', $trip);

        $trip->delete();

        return response()->noContent();
    }
}
