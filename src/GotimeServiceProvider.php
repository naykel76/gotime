<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;
use Naykel\Gotime\View\Components\Icon;
use Naykel\Gotime\View\Components\Markdown;
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
            Markdown::class,
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
        $this->registerComponentX('tooltip');

        // Tables and Lists
        $this->registerComponentX('table.table', 'table');
        $this->registerComponentX('table.th', 'table.th');
    }

    protected function registerLayoutComponents(): void
    {
        $this->registerComponentX('layouts.app', 'layouts.app');
        $this->registerComponentX('layouts.base', 'layouts.base');
    }

    protected function registerFormComponents(): void
    {
        // buttons
        $this->registerComponentX('button.base', 'button.base');
        $this->registerComponentX('button.default', 'button');
        $this->registerComponentX('button.primary', 'button.primary');
        $this->registerComponentX('button.secondary', 'button.secondary');

        // inputs
        // $this->registerComponentX('input.checkbox', 'checkbox');
        // $this->registerComponentX('input.ckeditor', 'ckeditor');
        // $this->registerComponentX('input.datepicker', 'datepicker');
        // $this->registerComponentX('input.editor', 'editor');
        // $this->registerComponentX('input.email', 'input.email');
        $this->registerComponentX('input.input', 'input');
        // $this->registerComponentX('input.password', 'input.password');
        // $this->registerComponentX('input.pikaday', 'pikaday');
        // $this->registerComponentX('input.radio', 'radio');
        // $this->registerComponentX('input.select', 'select');
        // $this->registerComponentX('input.slim-select', 'slim-select');
        // $this->registerComponentX('input.textarea', 'textarea');
    }

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

// $this->registerComponentX('alert');
// $this->registerComponentX('box.base', 'box');

// $this->registerComponentX('errors');

// $this->registerComponentX('livewire-search-input', 'search-input');
// $this->registerComponentX('loading-indicator');
// $this->registerComponentX('menu.menu-item', 'menu-item'); // wrapper for menu-link or other menu items
// $this->registerComponentX('menu.menu-link', 'menu-link');
// $this->registerComponentX('modal.base', 'modal'); // yes, theses are the same
// $this->registerComponentX('modal.base', 'modal.base'); // yes, theses are the same
// $this->registerComponentX('modal.variants.confirm', 'modal.confirm');
// $this->registerComponentX('modal.variants.delete', 'modal.delete');
// $this->registerComponentX('modal.variants.dialog', 'modal.dialog');
// $this->registerComponentX('resource-action', 'resource-action');
// $this->registerComponentX('spinner');
// $this->registerComponentX('toolbar.title-bar', 'title-bar');
