<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;

class ListCities extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = CityResource::class;
}
