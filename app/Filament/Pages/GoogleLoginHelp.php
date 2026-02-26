<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class GoogleLoginHelp extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Google Login Help';

    protected static ?string $title = 'Google Login Setup Guide';

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.pages.google-login-help';

    public function getViewData(): array
    {
        return [
            'appUrl' => config('app.url'),
            'clientId' => config('services.google.client_id'),
        ];
    }
}
