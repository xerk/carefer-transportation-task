<?php

namespace App\Filament\Resources\SeatResource\Pages;

use App\Filament\Resources\SeatResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;

class ListSeats extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = SeatResource::class;
}
