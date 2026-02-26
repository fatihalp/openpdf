<?php

namespace App\Filament\Resources\ConversionTasks\Pages;

use App\Filament\Resources\ConversionTasks\ConversionTaskResource;
use Filament\Resources\Pages\ViewRecord;

class ViewConversionTask extends ViewRecord
{
    protected static string $resource = ConversionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
