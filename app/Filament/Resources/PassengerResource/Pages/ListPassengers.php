<?php

namespace App\Filament\Resources\PassengerResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;
use App\Filament\Resources\PassengerResource;

class ListPassengers extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = PassengerResource::class;
}
