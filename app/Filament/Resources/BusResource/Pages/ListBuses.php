<?php

namespace App\Filament\Resources\BusResource\Pages;

use App\Filament\Resources\BusResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;

class ListBuses extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = BusResource::class;
}
