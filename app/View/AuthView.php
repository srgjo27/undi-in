<?php

namespace App\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class AuthView
{
    /**
     * Get the authentication layout view.
     */
    public static function main(): View
    {
        return ViewFacade::make('layouts.auth.main');
    }

    /**
     * Render the authentication layout view.
     */
    public static function render(): View
    {
        return self::main();
    }
}
