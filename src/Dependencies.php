<?php

declare(strict_types=1);

namespace Studio1902\Peak;

class Dependencies extends Peak
{
    /**
     * Dependencies constructor.
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct();

        $this->updateDependencies('package.json');
        $this->updateDependencies('composer.json', false);
        $this->removeNodeModules();
        $this->removeVendor();
    }

    /**
     * @return \Studio1902\Peak\Dependencies
     */
    public static function handle(): Dependencies
    {
        return new Dependencies();
    }

    /**
     * @return void
     */
    private function removeVendor(): void
    {
        $this->filesystem->deleteDirectory(
            base_path('vendor')
        );

        $this->filesystem->delete(
            base_path('composer.lock')
        );
    }

    /**
     * @return void
     */
    private function removeNodeModules(): void
    {
        $this->filesystem->deleteDirectory(
            base_path('node_modules')
        );

        $this->filesystem->delete(
            base_path('package-lock.json')
        );
    }
}
