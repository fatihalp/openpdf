<?php

namespace App\Filament\Resources\UploadedFiles\Pages;

use App\Filament\Resources\UploadedFiles\UploadedFileResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUploadedFile extends ViewRecord
{
    protected static string $resource = UploadedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
