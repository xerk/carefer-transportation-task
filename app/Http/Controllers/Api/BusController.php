<?php

namespace App\Http\Controllers\Api;

use App\Models\Bus;
use Illuminate\Http\Request;
use App\Http\Resources\BusResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusCollection;

class BusController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Bus::class);

        $search = $request->get('search', '');

        $buses = Bus::search($search)
            ->latest()
            ->paginate();

        return new BusCollection($buses);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Bus::class);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'max:255'],
            'capacity' => ['required', 'numeric'],
            'maintenance' => ['required', 'boolean'],
        ]);

        $bus = Bus::create($validated);

        return new BusResource($bus);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Bus $bus)
    {
        $this->authorize('view', $bus);

        return new BusResource($bus);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bus $bus)
    {
        $this->authorize('update', $bus);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'max:255'],
            'capacity' => ['required', 'numeric'],
            'maintenance' => ['required', 'boolean'],
        ]);

        $bus->update($validated);

        return new BusResource($bus);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Bus $bus)
    {
        $this->authorize('delete', $bus);

        $bus->delete();

        return response()->noContent();
    }
}
