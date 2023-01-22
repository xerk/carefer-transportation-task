<?php

namespace App\Filament\Resources\StationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;
use App\Filament\Resources\StationResource;

class ListStations extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = StationResource::class;
}
