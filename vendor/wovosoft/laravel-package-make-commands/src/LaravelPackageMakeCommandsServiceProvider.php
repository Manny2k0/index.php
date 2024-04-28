<?php

namespace Wovosoft\LaravelPackageMakeCommands;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wovosoft\LaravelPackageMakeCommands\Console\Commands\PackageControllerMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Console\Commands\PackageFactoryMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Console\Commands\PackageModelMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Console\Commands\PackagePolicyMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Console\Commands\PackageRequestMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Console\Commands\PackageSeederMakeCommand;

class LaravelPackageMakeCommandsServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-package-make-commands')
            ->hasCommands([
                PackageControllerMakeCommand::class,
                PackageModelMakeCommand::class,
                PackageFactoryMakeCommand::class,
                PackageSeederMakeCommand::class,
                PackagePolicyMakeCommand::class,
                PackageRequestMakeCommand::class,
            ]);
    }
}
