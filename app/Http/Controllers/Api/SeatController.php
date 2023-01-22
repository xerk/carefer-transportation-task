<?php

namespace App\Http\Controllers\Api;

use App\Models\Seat;
use Illuminate\Http\Request;
use App\Http\Resources\SeatResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeatCollection;

class SeatController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Seat::class);

        $search = $request->get('search', '');

        $seats = Seat::search($search)
            ->latest()
            ->paginate();

        return new SeatCollection($seats);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Seat::class);

        $validated = $request->validate([
            'referance' => ['required', 'max:255', 'string'],
            'number' => ['required', 'numeric'],
            'line' => ['required', 'in:a,b'],
        ]);

        $seat = Seat::create($validated);

        return new SeatResource($seat);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Seat $seat)
    {
        $this->authorize('view', $seat);

        return new SeatResource($seat);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seat $seat)
    {
        $this->authorize('update', $seat);

        $validated = $request->validate([
            'referance' => ['required', 'max:255', 'string'],
            'number' => ['required', 'numeric'],
            'line' => ['required', 'in:a,b'],
        ]);

        $seat->update($validated);

        return new SeatResource($seat);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Seat $seat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Seat $seat)
    {
        $this->authorize('delete', $seat);

        $seat->delete();

        return response()->noContent();
    }
}
