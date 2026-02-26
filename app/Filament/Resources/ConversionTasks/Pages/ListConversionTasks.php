<?php

namespace App\Filament\Resources\ConversionTasks\Pages;

use App\Filament\Resources\ConversionTasks\ConversionTaskResource;
use Filament\Resources\Pages\ListRecords;

class ListConversionTasks extends ListRecords
{
    protected static string $resource = ConversionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
