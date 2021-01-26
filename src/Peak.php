<?php

declare(strict_types=1);

namespace Studio1902\Peak;

use Illuminate\Filesystem\Filesystem;

abstract class Peak
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var string
     */
    protected string $dependencyKey;

    /**
     * @var bool
     */
    protected bool $isDevelopment = true;

    /**
     * Installer constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @return \Studio1902\Peak\Peak
     */
    abstract public static function handle(): Peak;

    /**
     * @return string
     */
    private function getDependencyKey(): string
    {
        return $this->dependencyKey;
    }

    /**
     * @param string $packageManager
     *
     * @return void
     */
    private function setDependencyKey(string $packageManager): void
    {
        if ($packageManager === (string) config('peak.composer.key')) {
            $this->dependencyKey = $this->isDevelopment() ? 'require-dev' : 'require';
        } else {
            $this->dependencyKey = $this->isDevelopment() ? 'devDependencies' : 'dependencies';
        }
    }

    /**
     * @return bool
     */
    private function isDevelopment(): bool
    {
        return $this->isDevelopment;
    }

    /**
     * @param bool $isDevelopment
     *
     * @return void
     */
    private function setIsDevelopment(bool $isDevelopment): void
    {
        $this->isDevelopment = $isDevelopment;
    }

    /**
     * @param string $file
     * @param bool $dev
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function updateDependencies(string $file, bool $dev = true): void
    {
        $this->setIsDevelopment($dev ?: false);

        $this->setDependencyKey(
            $packageManager = $this->getPackageManager($file)
        );

        $dependencies = $this->getDependencies(
            $packageManager,
            $this->getDependenciesFrom($file),
        );

        ksort($dependencies[$this->getDependencyKey()]);

        $this->saveDependenciesToFile($file, $dependencies);
    }

    /**
     * @param string $packageManager
     * @param array $dependencies
     *
     * @return array
     */
    private function getDependencies(string $packageManager, array $dependencies): array
    {
        $dependencyArray = array_key_exists($this->getDependencyKey(), $dependencies)
            ? $dependencies[$this->getDependencyKey()]
            : [];

        $dependencies[$this->getDependencyKey()] = $this->updateDependencyArray(
            $packageManager,
            $dependencyArray
        );

        return $dependencies;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function getPackageManager(string $file): string
    {
        return ($file === (string) config('peak.composer.config'))
            ? (string) config('peak.composer.key')
            : (string) config('peak.node.key');
    }

    /**
     * @param string $file
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getDependenciesFrom(string $file): array
    {
        return json_decode(
            $this->filesystem->get(base_path($file)),
            true
        );
    }

    /**
     * @param string $packageManager
     * @param array $dependencies
     *
     * @return array
     */
    private function updateDependencyArray(string $packageManager, array $dependencies): array
    {
        return ($packageManager === (string) config('peak.composer.key'))
            ? $this->mergeComposerDependencies($dependencies)
            : $this->mergeNodeDependencies($dependencies);
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    private function mergeComposerDependencies(array $dependencies): array
    {
        return $this->getDependencyKey() !== 'require'
            ? (array) config('peak.composer.require-dev') + $dependencies
            : (array) config('peak.composer.require') + $dependencies;
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    private function mergeNodeDependencies(array $dependencies): array
    {
        return $this->getDependencyKey() !== 'dependencies'
            ? (array) config('peak.node.devDependencies') + $dependencies
            : (array) config('peak.node.dependencies') + $dependencies;
    }

    /**
     * @param string $file
     * @param array $dependencies
     *
     * @return void
     */
    private function saveDependenciesToFile(string $file, array $dependencies): void
    {
        $this->filesystem->put(
            base_path($file),
            json_encode($dependencies, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }
}
