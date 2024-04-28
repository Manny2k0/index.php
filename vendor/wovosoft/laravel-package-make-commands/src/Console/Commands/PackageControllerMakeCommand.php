<?php

namespace Wovosoft\LaravelPackageMakeCommands\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Wovosoft\LaravelPackageMakeCommands\Traits\HasPackageMakeCommands;

#[AsCommand(name: 'make:package-controller')]
class PackageControllerMakeCommand extends ControllerMakeCommand
{
    use HasPackageMakeCommands;

    protected $name = 'make:package-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class for package';


    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name): string
    {
        $controllerNamespace = $this->getNamespace($name);


        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('creatable')) {
            $replace['abort(404);'] = '//';
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';
        $replace["use {$this->rootNamespace()}Http\Controllers\Controller;\n"] = "use App\Http\Controllers\Controller;\n";

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }
}
