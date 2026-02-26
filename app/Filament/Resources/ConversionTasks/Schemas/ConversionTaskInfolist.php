<?php

namespace App\Filament\Resources\ConversionTasks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ConversionTaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('id'),
            TextEntry::make('tool_key')->badge(),
            TextEntry::make('status')->badge(),
            TextEntry::make('user.email')->label('User')->placeholder('Visitor'),
            TextEntry::make('visitor_token')->placeholder('-')->limit(20),
            TextEntry::make('file_count'),
            TextEntry::make('total_size_bytes'),
            TextEntry::make('output_name')->placeholder('-'),
            TextEntry::make('output_mime')->placeholder('-'),
            TextEntry::make('output_size_bytes')->placeholder('-'),
            TextEntry::make('error_message')->placeholder('-')->columnSpanFull(),
            TextEntry::make('created_at')->dateTime(),
            TextEntry::make('started_at')->dateTime()->placeholder('-'),
            TextEntry::make('completed_at')->dateTime()->placeholder('-'),
        ]);
    }
}
