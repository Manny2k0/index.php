<?php

namespace Wovosoft\LaravelPackageMakeCommands\Console\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Wovosoft\LaravelPackageMakeCommands\Traits\HasPackageMakeCommands;

#[AsCommand(name: 'make:package-factory')]
class PackageFactoryMakeCommand extends FactoryMakeCommand
{
    use HasPackageMakeCommands;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:package-factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory for package';
}
