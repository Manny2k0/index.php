<?php

namespace Wovosoft\LaravelPackageMakeCommands\Console\Commands;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Wovosoft\LaravelPackageMakeCommands\Traits\HasPackageMakeCommands;

class PackageSeederMakeCommand extends SeederMakeCommand
{
    use HasPackageMakeCommands;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:package-seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class for a package';
}
