<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? $state : null)
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create'),
            TextInput::make('google_id')
                ->label('Google ID')
                ->maxLength(255),
            TextInput::make('avatar_url')
                ->url()
                ->maxLength(255),
            Select::make('locale')
                ->options([
                    'en' => 'English',
                    'tr' => 'Turkce',
                    'es' => 'Espanol',
                ])
                ->default('en')
                ->required(),
            Toggle::make('is_admin')
                ->label('Admin')
                ->default(false),
        ]);
    }
}
