<?php

namespace App\Filament\Resources\UploadedFiles\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UploadedFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('conversionTask.id')->label('Task')->sortable(),
                TextColumn::make('original_name')->searchable()->limit(40),
                TextColumn::make('mime_type')->placeholder('-'),
                TextColumn::make('size_bytes')->label('Bytes')->sortable(),
                TextColumn::make('disk')->badge(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('disk')->options([
                    'local' => 'local',
                    'browser' => 'browser',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
