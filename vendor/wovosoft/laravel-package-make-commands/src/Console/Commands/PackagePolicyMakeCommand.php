<?php

namespace Wovosoft\LaravelPackageMakeCommands\Console\Commands;

use Illuminate\Foundation\Console\PolicyMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Traits\HasPackageMakeCommands;


class PackagePolicyMakeCommand extends PolicyMakeCommand
{
    use HasPackageMakeCommands;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:package-policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy class for a package';
}
