<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\BusResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusCollection;

class UserBusesController extends Controller
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

        $buses = $user
            ->buses()
            ->search($search)
            ->latest()
            ->paginate();

        return new BusCollection($buses);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Bus::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'capacity' => ['required', 'numeric'],
            'maintenance' => ['required', 'boolean'],
        ]);

        $bus = $user->buses()->create($validated);

        return new BusResource($bus);
    }
}
