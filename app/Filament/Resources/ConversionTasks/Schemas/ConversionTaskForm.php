<?php

namespace App\Filament\Resources\ConversionTasks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConversionTaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('tool_key')->disabled(),
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                ])
                ->disabled(),
            TextInput::make('file_count')->numeric()->disabled(),
            TextInput::make('total_size_bytes')->numeric()->disabled(),
        ]);
    }
}
