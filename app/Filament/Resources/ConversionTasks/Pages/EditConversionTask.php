<?php

namespace App\Filament\Resources\ConversionTasks\Pages;

use App\Filament\Resources\ConversionTasks\ConversionTaskResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConversionTask extends EditRecord
{
    protected static string $resource = ConversionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
