<?php

namespace App\Http\Resources;

use DateTime;
use Cron\CronExpression;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TripSearchResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($trip) use($request) {
                $cron = CronExpression::factory($trip->cron_experations);
                $date = new DateTime($request->date);
                $nextRun = $cron->getNextRunDate($date);
                return [
                    'id' => $trip->id,
                    'name' => $trip->name,
                    'frequent' => $trip->frequent,
                    'type' => $trip->type,
                    'distance' => $trip->distance,
                    'price' => $trip->price,
                    'available_date' => $nextRun->format('Y-m-d') === $date->format('Y-m-d'),
                    'next_date' => $nextRun->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
