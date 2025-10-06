<?php

namespace App\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class BeView
{
    /**
     * Get the backend layout view.
     */
    public static function main(): View
    {
        return ViewFacade::make('layouts.be.main');
    }

    /**
     * Render the backend layout view.
     */
    public static function render(): View
    {
        return self::main();
    }
}
