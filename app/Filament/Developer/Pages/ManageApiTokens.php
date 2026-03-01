<?php

namespace App\Filament\Developer\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Laravel\Sanctum\PersonalAccessToken;

class ManageApiTokens extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'API Tokens';

    protected static ?string $title = 'Manage API Tokens';

    protected string $view = 'filament.developer.pages.manage-api-tokens';

    public ?string $plainTextToken = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(auth()->user()->tokens()->getQuery())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_used_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('createToken')
                    ->label('Generate New Token')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. My App'),
                    ])
                    ->action(function (array $data) {
                        $token = auth()->user()->createToken($data['name']);
                        $this->plainTextToken = $token->plainTextToken;
                        $this->dispatch('open-modal', id: 'display-token');
                    }),
            ])
            ->actions([
                            DeleteAction::make()
                                ->label('Revoke')
                                ->modalHeading('Revoke API Token')
                                ->action(fn (PersonalAccessToken $record) => $record->delete()),
                        ])
            ->emptyStateHeading('No API Tokens')
            ->emptyStateDescription('Generate an API token to access the conversions API programmatically.');
    }
}
