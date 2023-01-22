<?php

namespace App\Filament\Resources\TripResource\Pages;

use App\Filament\Resources\TripResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;

class ListTrips extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = TripResource::class;
}
