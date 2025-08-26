<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate as AuthFilamentMiddleware;

class RedirectIfNotFilamentAuthenticated extends AuthFilamentMiddleware
{
    // protected function redirectTo($request): ?string
    // {
    //     return route('filament.auth.auth.login');
    // }
}
