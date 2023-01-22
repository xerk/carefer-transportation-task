<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use App\Http\Resources\StationCollection;

class CityStationsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\City $city
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, City $city)
    {
        $this->authorize('view', $city);

        $search = $request->get('search', '');

        $stations = $city
            ->stations()
            ->search($search)
            ->latest()
            ->paginate();

        return new StationCollection($stations);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\City $city
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, City $city)
    {
        $this->authorize('create', Station::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'],
            'active' => ['required', 'boolean'],
        ]);

        $station = $city->stations()->create($validated);

        return new StationResource($station);
    }
}
