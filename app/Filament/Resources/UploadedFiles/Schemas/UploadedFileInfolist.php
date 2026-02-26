<?php

namespace App\Filament\Resources\UploadedFiles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UploadedFileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('id'),
            TextEntry::make('conversionTask.id')->label('Task ID'),
            TextEntry::make('user.email')->label('User')->placeholder('Visitor'),
            TextEntry::make('original_name'),
            TextEntry::make('mime_type')->placeholder('-'),
            TextEntry::make('size_bytes'),
            TextEntry::make('disk'),
            TextEntry::make('path')->columnSpanFull(),
            TextEntry::make('rotation_degrees'),
            TextEntry::make('created_at')->dateTime(),
        ]);
    }
}
