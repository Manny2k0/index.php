<?php

namespace Wovosoft\LaravelPackageMakeCommands\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Wovosoft\LaravelPackageMakeCommands\Traits\HasPackageMakeCommands;

#[AsCommand(name: 'make:package-model')]
class PackageModelMakeCommand extends ModelMakeCommand
{
    use HasPackageMakeCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:package-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class for a package';


    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory(): void
    {
        $factory = Str::studly($this->argument('name'));

        $this->call('make:package-factory', [
            'name'      => "{$factory}Factory",
            '--model'   => $this->qualifyClass($this->getNameInput()),
            '--package' => $this->option('package')
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration(): void
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name'       => "create_{$table}_table",
            '--create'   => $table,
            '--fullpath' => true,
            '--path'     => "packages/{$this->option('package')}/database/migrations"
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder(): void
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:package-seeder', [
            'name'      => "{$seeder}Seeder",
            '--package' => $this->option('package')
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController(): void
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:package-controller', array_filter([
            'name'       => "{$controller}Controller",
            '--model'    => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api'      => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--test'     => $this->option('test'),
            '--pest'     => $this->option('pest'),
            '--package'  => $this->option('package')
        ]));
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createPolicy(): void
    {
        $policy = Str::studly(class_basename($this->argument('name')));

        $this->call('make:package-policy', [
            'name'      => "{$policy}Policy",
            '--model'   => $this->qualifyClass($this->getNameInput()),
            '--package' => $this->option('package')
        ]);
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Models';
    }
}
