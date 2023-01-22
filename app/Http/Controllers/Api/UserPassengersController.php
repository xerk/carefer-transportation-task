<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PassengerResource;
use App\Http\Resources\PassengerCollection;

class UserPassengersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $passengers = $user
            ->passengers()
            ->search($search)
            ->latest()
            ->paginate();

        return new PassengerCollection($passengers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Passenger::class);

        $validated = $request->validate([
            'type' => ['required', 'in:guest,user'],
            'seat_id' => ['required', 'exists:seats,id'],
        ]);

        $passenger = $user->passengers()->create($validated);

        return new PassengerResource($passenger);
    }
}
