<?php

namespace App\Filament\Resources\ConversionTasks\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConversionTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('tool_key')->badge()->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('user.email')->label('User')->placeholder('Visitor')->searchable(),
                TextColumn::make('file_count')->sortable(),
                TextColumn::make('total_size_bytes')->label('Bytes')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('completed_at')->dateTime()->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                ]),
                SelectFilter::make('tool_key')->options([
                    'pdf_to_word' => 'PDF to Word',
                    'pdf_to_excel' => 'PDF to Excel',
                    'pdf_to_jpg' => 'PDF to JPG',
                    'compress_pdf' => 'Compress PDF',
                    'merge_pdf' => 'Merge PDF',
                    'word_to_pdf' => 'Word to PDF',
                    'excel_to_pdf' => 'Excel to PDF',
                    'jpg_to_pdf' => 'JPG to PDF',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
