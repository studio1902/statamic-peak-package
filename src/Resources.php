<?php

declare(strict_types=1);

namespace Studio1902\Peak;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Finder\SplFileInfo;

class Resources extends Peak
{
    /**
     * Resources constructor.
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct();

        $this
            ->updateWebpackConfiguration()
            ->updateTailwindConfiguration()
            ->updateFilesIn('collections', true)
            ->updateFilesIn('globals', true)
            ->updateFilesIn('blueprints/globals')
            ->updateFilesIn('blueprints/collections/pages')
            ->updateFilesIn('fieldsets')
            ->updateFilesIn('css')
            ->updateFilesIn('js')
            ->updateFilesIn('sass')
            ->updateViews('peak');
    }

    /**
     * @return \Studio1902\Peak\Resources
     */
    public static function handle(): Resources
    {
        return new Resources();
    }

    /**
     * @param string $path
     * @param bool $isContent
     *
     * @return \Studio1902\Peak\Resources
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function updateFilesIn(string $path, bool $isContent = false): Resources
    {
        $from = $this->getFromPath($path, $isContent);
        $to = $this->getToPath($path, $isContent);

        $this->ensureDirectoriesExists($path, $isContent);
        $this->ensureFileExists($from);

        collect($this->filesystem->allFiles($from))->each(
            fn (SplFileInfo $file): bool => $this->filesystem->copy(
                $from . $file->getFileName(),
                $to . $file->getFileName()
            )
        );

        return $this;
    }

    /**
     * @param string $to
     *
     * @return void
     */
    private function updateViews(string $to): void
    {
        $from = __DIR__ . '/../resources/views/' . $to;
        $this->filesystem->copyDirectory($from, resource_path('views/' . $to));
    }

    /**
     * @param string $path
     * @param bool $isContent
     *
     * @return void
     */
    private function ensureDirectoriesExists(string $path, bool $isContent): void
    {
        ($isContent)
            ? $this->filesystem->ensureDirectoryExists(base_path('content/' . $path))
            : $this->filesystem->ensureDirectoryExists(resource_path($path));
    }

    /**
     * @param string $path
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function ensureFileExists(string $path): void
    {
        if ($this->filesystem->missing($path)) {
            $this->fileNotFound($path);
        }
    }

    /**
     * @return \Studio1902\Peak\Resources
     */
    private function updateWebpackConfiguration(): Resources
    {
        $this->filesystem->copy(
            __DIR__ . '/../stubs/webpack.mix.js',
            base_path('webpack.mix.js')
        );

        return $this;
    }

    /**
     * @return \Studio1902\Peak\Resources
     */
    private function updateTailwindConfiguration(): Resources
    {
        $from = __DIR__ . '/../stubs/tailwind/';

        collect($this->filesystem->allFiles($from))->each(
            fn (SplFileInfo $file): bool => $this->filesystem->copy(
                $from . $file->getFileName(),
                base_path($file->getFileName())
            )
        );

        return $this;
    }

    /**
     * @param string $path
     * @param bool $isContent
     *
     * @return string
     */
    private function getFromPath(string $path, bool $isContent): string
    {
        return ($isContent)
            ? __DIR__ . '/../stubs/content/' . $path . '/'
            : __DIR__ . '/../resources/' . $path . '/';
    }

    /**
     * @param string $path
     * @param bool $isContent
     *
     * @return string
     */
    private function getToPath(string $path, bool $isContent): string
    {
        return ($isContent)
            ? base_path('content/' . $path . '/')
            : resource_path($path . '/');
    }

    /**
     * @param string $path
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function fileNotFound(string $path): void
    {
        throw new FileNotFoundException(sprintf(
            'File does not exist at path %s.',
            $path
        ));
    }
}
