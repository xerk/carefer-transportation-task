<?php

namespace App\Http\Controllers\Api;

use App\Models\Station;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use App\Http\Resources\StationCollection;

class StationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Station::class);

        $search = $request->get('search', '');

        $stations = Station::search($search)
            ->latest()
            ->paginate();

        return new StationCollection($stations);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Station::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'city_id' => ['required', 'exists:cities,id'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
            'active' => ['required', 'boolean'],
        ]);

        $station = Station::create($validated);

        return new StationResource($station);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Station $station
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Station $station)
    {
        $this->authorize('view', $station);

        return new StationResource($station);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Station $station
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Station $station)
    {
        $this->authorize('update', $station);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'city_id' => ['required', 'exists:cities,id'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
            'active' => ['required', 'boolean'],
        ]);

        $station->update($validated);

        return new StationResource($station);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Station $station
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Station $station)
    {
        $this->authorize('delete', $station);

        $station->delete();

        return response()->noContent();
    }
}
