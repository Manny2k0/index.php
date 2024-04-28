<?php

namespace Wovosoft\LaravelPackageMakeCommands\Traits;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Console\Input\InputOption;
use function Laravel\Prompts\select;

trait HasPackageMakeCommands
{
    protected string $packagePath;

    private function getPackages(): array
    {
        //get list of folders in packages of depth 2 like wovosoft/pos-hrm and return in format wovosoft/pos-hrm
        return collect(glob(base_path('packages/*/*')))
            ->map(fn($item) => str_replace(base_path('packages/'), '', $item))
            ->toArray();
    }

    private function hasPackage(string $package): bool
    {
        return in_array($package, $this->getPackages());
    }

    /**
     * @throws Exception
     */
    private function initPackage(): static
    {
        if ($this->option('package')) {
            if (!$this->hasPackage($this->option('package'))) {
                throw new Exception("Package not found");
            }
        } else {
            $this->input->setOption('package', select('Select Package', $this->getPackages()));
        }

        $this->packagePath = base_path('packages/' . $this->option('package'));
        return $this;
    }

    /**
     * @throws FileNotFoundException
     * @throws \Exception
     */
    public function handle(): ?bool
    {
        $this->initPackage();
        return parent::handle();
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        $namespace = str($this->option('package'))->explode('/')->implode(fn($i) => Str::studly($i), '\\');
        return match ($this->type) {
            'Seeder' => $namespace . '\\Database\Seeders\\',
            default  => $namespace
        };
    }


    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $path = match ($this->type) {
            'Factory' => $this->packagePath . '/database/factories',
            'Seeder'  => is_dir($this->packagePath . '/database/seeds')
                ? $this->packagePath . '/database/seeds/'
                : $this->packagePath . '/database/seeders/',
            default   => $this->packagePath . '/src',
        };

        return $path . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : dirname((new ReflectionClass(get_parent_class($this)))->getFileName()) . $stub;
    }

    protected function getOptions(): array
    {
        return [
            ...parent::getOptions(),
            ['package', 'P', InputOption::VALUE_OPTIONAL, 'Provide package name'],
        ];
    }
}
