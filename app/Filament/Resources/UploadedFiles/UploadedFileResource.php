<?php

namespace App\Filament\Resources\UploadedFiles;

use App\Filament\Resources\UploadedFiles\Pages\ListUploadedFiles;
use App\Filament\Resources\UploadedFiles\Pages\ViewUploadedFile;
use App\Filament\Resources\UploadedFiles\Schemas\UploadedFileForm;
use App\Filament\Resources\UploadedFiles\Schemas\UploadedFileInfolist;
use App\Filament\Resources\UploadedFiles\Tables\UploadedFilesTable;
use App\Models\UploadedFile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UploadedFileResource extends Resource
{
    protected static ?string $model = UploadedFile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Uploaded Files';

    public static function form(Schema $schema): Schema
    {
        return UploadedFileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UploadedFileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UploadedFilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUploadedFiles::route('/'),
            'view' => ViewUploadedFile::route('/{record}'),
        ];
    }
}
