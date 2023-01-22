<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Resources\CityResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityCollection;

class CityController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', City::class);

        $search = $request->get('search', '');

        $cities = City::search($search)
            ->latest('id')
            ->paginate();

        return new CityCollection($cities);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', City::class);

        $validated = $request->validate([
            'governorate_id' => ['required', 'exists:governorates,id'],
            'name' => ['required', 'max:255', 'string'],
            'key' => ['nullable', 'max:255', 'string'],
        ]);

        $city = City::create($validated);

        return new CityResource($city);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\City $city
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, City $city)
    {
        $this->authorize('view', $city);

        return new CityResource($city);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\City $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $this->authorize('update', $city);

        $validated = $request->validate([
            'governorate_id' => ['required', 'exists:governorates,id'],
            'name' => ['required', 'max:255', 'string'],
            'key' => ['nullable', 'max:255', 'string'],
        ]);

        $city->update($validated);

        return new CityResource($city);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\City $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, City $city)
    {
        $this->authorize('delete', $city);

        $city->delete();

        return response()->noContent();
    }
}
