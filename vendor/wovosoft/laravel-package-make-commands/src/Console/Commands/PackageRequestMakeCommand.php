<?php

namespace Wovosoft\LaravelPackageMakeCommands\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Wovosoft\LaravelPackageMakeCommands\Traits\HasPackageMakeCommands;

#[AsCommand(name: 'make:package-request')]
class PackageRequestMakeCommand extends RequestMakeCommand
{
    use HasPackageMakeCommands;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:package-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for package';
}
