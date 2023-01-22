<?php

namespace App\Http\Controllers\Api;

use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeatCollection;

class BusSeatsController extends Controller
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

        $seats = $bus
            ->seats()
            ->search($search)
            ->latest()
            ->paginate();

        return new SeatCollection($seats);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Bus $bus, Seat $seat)
    {
        $this->authorize('update', $bus);

        $bus->seats()->syncWithoutDetaching([$seat->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Bus $bus, Seat $seat)
    {
        $this->authorize('update', $bus);

        $bus->seats()->detach($seat);

        return response()->noContent();
    }
}
