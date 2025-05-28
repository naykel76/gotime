<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;
use Naykel\Gotime\View\Components\Filepond;
use Naykel\Gotime\View\Components\Icon;
use Naykel\Gotime\View\Components\Markdown;
use Naykel\Gotime\View\Components\Menu;
use Naykel\Gotime\View\Components\Sidebar;
use Naykel\Gotime\View\Layouts\AppLayout;

class GotimeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/gotime.php', 'gotime');
        $this->mergeConfigFrom(__DIR__ . '/../config/services.php', 'services');
        $this->mergeConfigFrom(__DIR__ . '/../config/markdown.php', 'markdown');

        $this->app->singleton('filemanagement', function ($app) {
            return new \Naykel\Gotime\Services\FileManagementService;
        });
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
        ], 'gotime-all-layouts');

        // only publish the main app layout and partials
        $this->publishes([
            __DIR__ . '/../resources/views/components/layouts/app.blade.php' => resource_path('views/components/layouts/app.blade.php'),
            __DIR__ . '/../resources/views/components/layouts/partials' => resource_path('views/components/layouts/partials'),
        ], 'gotime-app-layouts');

        $this->loadViewComponentsAs('gt', [
            AppLayout::class,
            Filepond::class,
            Icon::class,
            Markdown::class,
            Menu::class,
            Sidebar::class,
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
        $this->registerComponentX('menu.menu-item', 'menu-item'); // wrapper for menu-link or other menu items
        $this->registerComponentX('menu.menu-link', 'menu-link');

        // Alerts, Notifications, and Messages
        $this->registerComponentX('alert');
        $this->registerComponentX('errors');
        $this->registerComponentX('toast');

        // Buttons
        // NK::REFACTOR handling multiple variations is little primitive, but it works!
        $this->registerComponentX('button.submit', 'submit');
        $this->registerComponentX('button.variants.save', 'button.save');
        $this->registerComponentX('v2/button.base', 'button.base');
        $this->registerComponentX('v2/button.variants.default', 'button');
        $this->registerComponentX('v2/button.variants.primary', 'button.primary');
        $this->registerComponentX('v2/button.variants.secondary', 'button.secondary');

        $this->registerComponentX('v2/resource-action', 'resource-action');

        // layouts
        $this->registerComponentX('layouts.base', 'gotime-layouts.base');
        $this->registerComponentX('layouts.partials.two-column-responsive');

        // Livewire special components
        $this->registerComponentX('livewire-search-input', 'search-input');

        $this->registerComponentX('box.base', 'box');

        // Modals and Boxes
        $this->registerComponentX('modal.base', 'modal'); // yes, theses are the same
        $this->registerComponentX('modal.base', 'modal.base'); // yes, theses are the same
        $this->registerComponentX('modal.variants.confirm', 'modal.confirm');
        $this->registerComponentX('modal.variants.delete', 'modal.delete');
        $this->registerComponentX('modal.variants.dialog', 'modal.dialog');

        // table components
        $this->registerComponentX('v2.table.th', 'table.th');
        $this->registerComponentX('v2.table.table', 'table');

        // toolbars
        $this->registerComponentX('toolbar.title-bar', 'title-bar');
    }

    protected function registerFormComponents(): void
    {
        $this->registerComponentX('input.datepicker', 'datepicker');
        $this->registerComponentX('input.editor', 'editor');
        $this->registerComponentX('input.email');
        $this->registerComponentX('input.input', 'input');
        $this->registerComponentX('input.password');
        $this->registerComponentX('input.pikaday', 'pikaday');
        $this->registerComponentX('input.radio', 'radio');
        $this->registerComponentX('input.textarea', 'textarea');
        $this->registerComponentX('v2.input.checkbox', 'checkbox');
        $this->registerComponentX('v2.input.ckeditor', 'ckeditor');
        $this->registerComponentX('v2.input.select', 'select');
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
