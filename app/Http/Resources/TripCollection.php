<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TripCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request)->map(function ($trip) {
                d
            return [
                'id' => $trip->id,
                'pickup_id' => $trip->pickup_id,
                'destination_id' => $trip->destination_id,
                'pickup' => $trip->pickup,
                'destination' => $trip->destination,
                'price' => $trip->price,
                'available_seats' => $trip->availableSeats(),
                'created_at' => $trip->created_at,
                'updated_at' => $trip->updated_at,
            ];
        });
    }
}
