<?php

namespace App\Http\Controllers\Api;

use App\Models\Governorate;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityCollection;

class GovernorateCitiesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Governorate $governorate
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Governorate $governorate)
    {
        $this->authorize('view', $governorate);

        $search = $request->get('search', '');

        $cities = $governorate
            ->allCities()
            ->search($search)
            ->latest()
            ->paginate();

        return new CityCollection($cities);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Governorate $governorate
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Governorate $governorate)
    {
        $this->authorize('create', City::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'key' => ['nullable', 'max:255', 'string'],
        ]);

        $city = $governorate->allCities()->create($validated);

        return new CityResource($city);
    }
}
