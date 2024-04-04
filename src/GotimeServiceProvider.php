<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;
use Naykel\Gotime\View\Components\Icon;
use Naykel\Gotime\View\Components\Menu;
use Naykel\Gotime\View\Components\Sidebar;
use Naykel\Gotime\View\Layouts\AppLayout;

class GotimeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/naykel.php', 'naykel');
    }

    public function boot()
    {
        $this->commands([InstallCommand::class]);
        $this->configureComponents();


        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        $this->publishes([
            __DIR__ . '/../config/naykel.php' => config_path('naykel.php'),
        ], 'gotime-config');

        $this->publishes([
            __DIR__ . '/../resources/views/layouts' => resource_path('views/layouts'),
        ], 'gotime-views');

        $this->loadViewComponentsAs('gt', [
            AppLayout::class,
            Icon::class,
            Menu::class,
            Sidebar::class,
        ]);
    }

    /**
     * Configure the Gotime Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        // layouts
        Blade::component('gotime::components.layouts.base', 'gotime-layouts.base');
    }
}
