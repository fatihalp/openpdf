<?php

namespace App\Filament\Resources\UploadedFiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UploadedFileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('original_name')->disabled(),
            TextInput::make('mime_type')->disabled(),
            TextInput::make('size_bytes')->numeric()->disabled(),
            TextInput::make('path')->disabled(),
        ]);
    }
}
