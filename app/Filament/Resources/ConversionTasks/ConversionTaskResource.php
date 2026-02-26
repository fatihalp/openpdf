<?php

namespace App\Filament\Resources\ConversionTasks;

use App\Filament\Resources\ConversionTasks\Pages\ListConversionTasks;
use App\Filament\Resources\ConversionTasks\Pages\ViewConversionTask;
use App\Filament\Resources\ConversionTasks\Schemas\ConversionTaskForm;
use App\Filament\Resources\ConversionTasks\Schemas\ConversionTaskInfolist;
use App\Filament\Resources\ConversionTasks\Tables\ConversionTasksTable;
use App\Models\ConversionTask;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConversionTaskResource extends Resource
{
    protected static ?string $model = ConversionTask::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Conversion Tasks';

    public static function form(Schema $schema): Schema
    {
        return ConversionTaskForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConversionTaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConversionTasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConversionTasks::route('/'),
            'view' => ViewConversionTask::route('/{record}'),
        ];
    }
}
