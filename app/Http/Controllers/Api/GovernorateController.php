<?php

namespace App\Http\Controllers\Api;

use App\Models\Governorate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GovernorateResource;
use App\Http\Resources\GovernorateCollection;

class GovernorateController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Governorate::class);

        $search = $request->get('search', '');

        $governorates = Governorate::search($search)
            ->latest()
            ->paginate();

        return new GovernorateCollection($governorates);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Governorate::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'key' => ['required', 'max:255', 'string'],
        ]);

        $governorate = Governorate::create($validated);

        return new GovernorateResource($governorate);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Governorate $governorate
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Governorate $governorate)
    {
        $this->authorize('view', $governorate);

        return new GovernorateResource($governorate);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Governorate $governorate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Governorate $governorate)
    {
        $this->authorize('update', $governorate);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'key' => ['required', 'max:255', 'string'],
        ]);

        $governorate->update($validated);

        return new GovernorateResource($governorate);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Governorate $governorate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Governorate $governorate)
    {
        $this->authorize('delete', $governorate);

        $governorate->delete();

        return response()->noContent();
    }
}
