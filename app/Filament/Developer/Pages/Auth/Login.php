<?php

namespace App\Filament\Developer\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    protected string $view = 'filament.developer.pages.auth.login';

    // Disable default Filament login form to force Google Login only
    public function hasLogo(): bool
    {
        return true;
    }
}
