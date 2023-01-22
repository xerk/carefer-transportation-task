<?php

namespace App\Http\Controllers\Api\Client;

use DateTime;
use Carbon\Carbon;
use App\Models\Trip;
use Cron\CronExpression;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Http\Resources\TripCollection;
use App\Http\Resources\TripSearchResource;

class TripController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'pickup_id' => ['required', 'numeric', 'exists:stations,id'],
            'destination_id' => ['required', 'numeric', 'exists:stations,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $trips = Trip::whereActive(true)->wherePickupId($validated['pickup_id'])
            ->whereDestinationId($validated['destination_id'])
            ->latest()
            ->paginate();

        return new TripSearchResource($trips);
    }
}
