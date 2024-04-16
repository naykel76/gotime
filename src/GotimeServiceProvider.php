<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;
use Naykel\Gotime\View\Components\Icon;
use Naykel\Gotime\View\Components\Markdown;
use Naykel\Gotime\View\Components\Menu;
use Naykel\Gotime\View\Components\Sidebar;
use Naykel\Gotime\View\Layouts\AppLayout;

class GotimeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/naykel.php', 'naykel');
        $this->mergeConfigFrom(__DIR__ . '/../config/services.php', 'services');
    }

    public function boot()
    {
        $this->commands([InstallCommand::class]);
        $this->registerComponents();
        $this->registerFormComponents();


        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        $this->publishes([
            __DIR__ . '/../config/naykel.php' => config_path('naykel.php'),
        ], 'gotime-config');

        $this->publishes([
            __DIR__ . '/../resources/views/components/layouts' => resource_path('views/components/layouts'),
        ], 'gotime-views');

        $this->loadViewComponentsAs('gt', [
            AppLayout::class,
            Icon::class,
            Menu::class,
            Sidebar::class,
            Markdown::class
        ]);
    }

    /**
     * Configure the Gotime Blade components.
     *
     * @return void
     */
    protected function registerComponents()
    {
        $this->registerComponentX('loading-indicator');
        $this->registerComponentX('spinner');
        $this->registerComponentX('tooltip');

        // Alerts, Notifications, and Messages
        $this->registerComponentX('toast');
        
        // Buttons
        $this->registerComponentX('button.submit', 'submit');


        // layouts
        $this->registerComponentX('layouts.base', 'gotime-layouts.base');
    }

    protected function registerFormComponents(): void
    {
        // controls
        $this->registerComponentX('input.checkbox', 'checkbox');
        $this->registerComponentX('input.email');
        $this->registerComponentX('input.input', 'input');
        $this->registerComponentX('input.password');
        $this->registerComponentX('input.textarea', 'textarea');
    }

    /**
     * @param string $component (path and name)
     * @param string $alias use when component name and path are !=
     * @param string $prefix (gtl for special livewire components)
     */
    protected function registerComponentX(string $component, string $alias = null, string $prefix = 'gt'): void
    {
        Blade::component('gotime::components.' . $component, "$prefix-" . ($alias ?? $component));
    }
}
