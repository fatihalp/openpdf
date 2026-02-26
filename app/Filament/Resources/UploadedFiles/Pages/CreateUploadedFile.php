<?php

namespace App\Filament\Resources\UploadedFiles\Pages;

use App\Filament\Resources\UploadedFiles\UploadedFileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUploadedFile extends CreateRecord
{
    protected static string $resource = UploadedFileResource::class;
}
