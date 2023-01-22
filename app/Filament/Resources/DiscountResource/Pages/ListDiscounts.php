<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasDescendingOrder;
use App\Filament\Resources\DiscountResource;

class ListDiscounts extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = DiscountResource::class;
}
