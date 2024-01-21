<?php

namespace Naykel\Gotime;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Naykel\Gotime\Commands\InstallCommand;

use Naykel\Gotime\View\Components\Menu;
use Naykel\Gotime\View\Components\Icon;
use Naykel\Gotime\View\Components\Parsedown;
use Naykel\Gotime\View\Components\Sidebar;
use Naykel\Gotime\View\Layouts\AppLayout;

class GotimeServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Merge services configuration...
        $this->mergeConfigFrom(
            __DIR__ . '/../stubs/config/services.php',
            'services',
        );

        // Merge Naykel configuration...
        $this->mergeConfigFrom(
            __DIR__ . '/../stubs/config/naykel.php',
            'naykel'
        );
    }

    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        $this->configureComponents();
        $this->registerFormComponents();
        $this->registerIconComponents();

        $this->commands([InstallCommand::class]);

        /** Load Package Components
         * ==================================================================
         *
         */

        //  depreciated
        $this->loadViewComponentsAs('gotime', [
            AppLayout::class,

        ]);

        $this->loadViewComponentsAs('gt', [
            Icon::class,
            Menu::class,
            Parsedown::class,
            Sidebar::class,
        ]);

        /** Assets
         * ==================================================================
         */
        $this->publishes(
            [
                __DIR__ . '/../stubs/resources/js' => resource_path('js'),
            ],
            'gotime-assets'
        );

        /** Publish Config
         * ==================================================================
         * There is no need to publish the configuration file as it is merged
         * when the component is registered. Furthermore, any new keys created
         * inside the package will be automatically added so there is no need
         * to re-publish.
         */

        $this->publishes([
            __DIR__ . '/../stubs/config/naykel.php' => config_path('naykel.php'),
        ], 'gotime-config');

        /** Publish Layout Views
         * ==================================================================
         *
         */
        $this->publishes([
            __DIR__ . '/../resources/views/layouts/partials' => resource_path('views/layouts/partials'),
        ], 'gotime-views');

        /**
         * Search macro for data tables
         */
        Builder::macro('search', function ($field, $string) {
            return $string ? $this->where($field, 'like', '%' . $string . '%') : $this;
        });
    }

    /**
     * Configure the Gotime Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {

            //
            $this->registerComponentX('menu-link');
            $this->registerComponentX('markdown');

            // Notifications, Flash and Messages
            $this->registerComponent('errors');

            // other
            $this->registerComponentX('accordion');
            $this->registerComponentX('alert');
            $this->registerComponentX('loading-indicator');
            $this->registerComponentX('tooltip');
            $this->registerComponentX('v2.toast', 'toast');
            $this->registerComponentX('v2.spinner', 'spinner');

            // buttons
            $this->registerComponentX('v2.button.button', 'button');
            $this->registerComponentX('v2.button.submit', 'submit');
            $this->registerComponentX('v2.button.variants.primary', 'button-primary');
            $this->registerComponentX('v2.button.variants.secondary', 'button-secondary');

            $this->registerComponentX('button.create', 'button-create');
            $this->registerComponentX('button.delete', 'button-delete');
            $this->registerComponentX('button.edit', 'button-edit');
            $this->registerComponentX('button.save', 'button-save');

            // layouts
            $this->registerComponentX('layouts.admin-form', 'admin-form');
            Blade::component('gotime::components.layouts.base', 'gotime-layouts.base');

            // table components
            $this->registerComponentX('v2.table.th', 'table.th');

            // toolbars
            $this->registerComponentX('toolbar.search-sort-toolbar', 'search-sort-toolbar');
            $this->registerComponentX('toolbar.actions-toolbar', 'actions-toolbar');
            $this->registerComponentX('toolbar.title-bar', 'title-bar');

            // modals and alerts
            $this->registerComponentX('modal');
            $this->registerComponentX('modal.dialog');
            $this->registerComponentX('modal.save');
            $this->registerComponentX('modal.delete');
            $this->registerComponentX('modal.image');
            $this->registerComponentX('modal.confirmation');
        });
    }

    // DEPRECIATED
    protected function registerIconComponents()
    {
        $this->registerIconComponentsFromDirectory('icon'); // base directory
        $this->registerIconComponentsFromDirectory('icon/payment');
        $this->registerIconComponentsFromDirectory('icon/logos');

        $this->registerComponentX('icon'); // this is not the same as registerIconComponents
    }

    protected function registerFormComponents(): void
    {
        $this->registerComponentX('form');

        // Form Components
        $this->registerComponentX('v2.input.partials.label', 'label');
        $this->registerComponentX('v2.input.partials.control-group', 'control-group');

        // Form Controls and Inputs
        $this->registerComponentX('input.checkbox', 'checkbox');
        $this->registerComponentX('input.choices', 'choices');
        $this->registerComponentX('input.datepicker', 'datepicker');
        $this->registerComponentX('input.email');
        $this->registerComponentX('input.file');
        $this->registerComponentX('input.filepond', 'filepond');
        $this->registerComponentX('input.number');
        $this->registerComponentX('input.password');
        $this->registerComponentX('input.textarea', 'textarea');
        $this->registerComponentX('v2.input.ckeditor', 'ckeditor');
        $this->registerComponentX('v2.input.ckeditor-basic', 'ckeditor-basic');
        $this->registerComponentX('v2.input.ckeditor-inline', 'ckeditor-inline');
        $this->registerComponentX('v2.input.input', 'input');
        $this->registerComponentX('v2.input.select', 'select');
        $this->registerComponentX('v2.input.trix', 'trix');

        // Livewire special components
        $this->registerComponentX('v2.livewire-search-input', 'search-input', 'gtl');
    }

    /**
     * Loop through the directory and register all the components
     */
    protected function registerIconComponentsFromDirectory(string $dir): void
    {
        $filesInFolder = \File::files(__dir__ . "/../resources/views/components/$dir");

        foreach ($filesInFolder as $path) {
            // Remove the '.blade.php' extension from the filename
            $component = basename($path, '.blade.php');
            $this->registerIconComponent($component, $dir);
        }
    }

    protected function registerIconComponent(string $component, string $dir, string $prefix = 'gt-icon-'): void
    {
        if (!empty($dir)) {
            $dir = ".$dir";
        }
        Blade::component("gotime::components{$dir}.{$component}", "{$prefix}{$component}");
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

    /**
     * THIS IS DEPRECIATED, USE registerComponentX ????
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('gotime::components.' . $component, 'gotime-' . $component);
    }
}
