<?php

namespace Wovosoft\LaravelPackageMakeCommands\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelPackageMakeCommands extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-package-make-commands';
    }
}
