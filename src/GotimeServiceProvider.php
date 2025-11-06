<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;
use Naykel\Gotime\View\Components\Icon;
use Naykel\Gotime\View\Components\Nav;

class GotimeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/gotime.php', 'gotime');
        $this->mergeConfigFrom(__DIR__ . '/../config/services.php', 'services');
        $this->mergeConfigFrom(__DIR__ . '/../config/markdown.php', 'markdown');
    }

    public function boot()
    {
        $this->registerComponents();
        $this->registerLayoutComponents();
        $this->registerFormComponents();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        $this->loadViewComponentsAs('gt', [
            // Filepond::class,
            Icon::class,
            // Markdown::class,
            Nav::class,
            // Sidebar::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([InstallCommand::class]);

            $this->publishes([
                __DIR__ . '/../stubs/routes/web.php' => base_path('routes/web.php'),
            ], 'gotime-routes');

            $this->publishes([
                __DIR__ . '/../config/naykel.php' => config_path('naykel.php'),
            ], 'gotime-config');

            $this->publishes([
                __DIR__ . '/../resources/views/components/layouts' => resource_path('views/components/layouts'),
            ], 'gotime-layouts');
        }
    }

    protected function registerComponents(): void
    {
        // Alerts, Notifications, and Messages
        $this->registerComponentX('toast');
    }

    protected function registerLayoutComponents(): void
    {
        $this->registerComponentX('layouts.app', 'layouts.app');
        $this->registerComponentX('layouts.base', 'layouts.base');
    }

    protected function registerFormComponents(): void {}

    /**
     * @param  string  $component  (path and name)
     * @param  string  $alias  use when component name and path are !=
     * @param  string  $prefix  (gtl for special livewire components)
     */
    protected function registerComponentX(string $component, ?string $alias = null, string $prefix = 'gt'): void
    {
        Blade::component('gotime::components.' . $component, "$prefix-" . ($alias ?? $component));
    }
}
