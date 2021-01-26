<?php

declare(strict_types=1);

namespace Studio1902\Peak\Commands;

use Illuminate\Console\Command;
use Studio1902\Peak\Dependencies;
use Studio1902\Peak\Resources;

class PeakInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peak:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Peak Statamic Starter Kit';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle(): int
    {
        $this->resources([
            'blueprints',
            'css',
            'fieldsets',
            'js',
            'sass',
            'views'
        ]);

        $this->dependencies();

        return 0;
    }

    /**
     * @return void
     */
    private function dependencies(): void
    {
        Dependencies::handle();
        $this->info('Node Modules has been updated, please run <comment>[npm install]</comment> & <comment>[npm run dev]</comment> again.');
        $this->newLine();

        $this->info('Composer has been updated, please run <comment>[composer install]</comment> again.');
        $this->newLine();
    }

    /**
     * @param array $resources
     *
     * @return void
     */
    private function resources(array $resources): void
    {
        Resources::handle();

        $headers = ['Resource', 'Location'];

        foreach ($resources as $resource) {
            $resources[] = [ucfirst($resource), resource_path($resource)];
        }

        $this->newLine();
        $this->info('Published Resources');
        $this->newLine();
        $this->table($headers, $resources);
        $this->newLine();
    }
}
