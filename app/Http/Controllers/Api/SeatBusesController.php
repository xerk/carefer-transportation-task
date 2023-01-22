<?php

namespace App\Http\Controllers\Api;

use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusCollection;

class SeatBusesController extends Controller
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

        $buses = $seat
            ->buses()
            ->search($search)
            ->latest()
            ->paginate();

        return new BusCollection($buses);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Seat $seat, Bus $bus)
    {
        $this->authorize('update', $seat);

        $seat->buses()->syncWithoutDetaching([$bus->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Seat $seat, Bus $bus)
    {
        $this->authorize('update', $seat);

        $seat->buses()->detach($bus);

        return response()->noContent();
    }
}
