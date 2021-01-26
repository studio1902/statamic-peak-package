<?php

declare(strict_types=1);

namespace Studio1902\Peak;

use Illuminate\Support\ServiceProvider;
use Studio1902\Peak\Commands\PeakInstallCommand;

class PeakServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/peak.php', 'peak');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PeakInstallCommand::class,
            ]);
        }

        $this->publishConfiguration();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'peak');
    }

    /**
     * @return \Studio1902\Peak\PeakServiceProvider
     */
    private function publishConfiguration(): PeakServiceProvider
    {
        $this->publishes([
            __DIR__ . '/../config/peak.php' => config_path('peak.php'),
        ], 'peak-config');

        return $this;
    }
}
