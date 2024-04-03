<?php

namespace Naykel\Gotime;

use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;

class GotimeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/naykel.php', 'naykel');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        $this->commands([InstallCommand::class]);

        $this->publishes([
            __DIR__ . '/../config/naykel.php' => config_path('naykel.php'),
        ], 'gotime-config');

        $this->publishes([
            __DIR__ . '/../resources/views/layouts' => resource_path('views/layouts'),
        ], 'gotime-views');
    }
}
