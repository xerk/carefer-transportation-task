<?php

namespace App\Filament\Resources\GovernorateResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;
use App\Filament\Resources\GovernorateResource;

class ListGovernorates extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = GovernorateResource::class;
}
