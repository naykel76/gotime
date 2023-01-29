<?php

namespace Naykel\Gotime;

use Illuminate\View\Compilers\BladeCompiler;
use Naykel\Gotime\View\Components\Parsedown;
use Naykel\Gotime\Commands\InstallCommand;
use Illuminate\Database\Eloquent\Builder;
use Naykel\Gotime\View\Layouts\AppLayout;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\View\Components\Menu;
use Illuminate\Support\Facades\Blade;

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

        $this->app->bind('routebuilder', function ($app) {
            return new RouteBuilder();
        });
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
        $this->loadViewComponentsAs('gotime', [
            AppLayout::class,
            Parsedown::class,
        ]);

        $this->loadViewComponentsAs('gt', [
            Menu::class,
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
        $this->publishes(
            [
                __DIR__ . '/../stubs/config/naykel.php', 'naykel'
            ],
            'gotime-config'
        );

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

            // Notifications, Flash and Messages
            $this->registerComponent('errors');

            // other
            $this->registerComponentX('icon');
            $this->registerComponentX('accordion');
            $this->registerComponentX('alert');
            $this->registerComponentX('loading-indicator');
            $this->registerComponentX('notification-toaster');


            // buttons
            $this->registerComponentX('button.button', 'button');
            $this->registerComponentX('button.create', 'button-create');
            $this->registerComponentX('button.delete', 'button-delete');
            $this->registerComponentX('button.edit', 'button-edit');
            $this->registerComponentX('button.primary', 'button-primary');
            $this->registerComponentX('button.quick-add');
            $this->registerComponentX('button.save', 'button-save');
            $this->registerComponentX('button.secondary', 'button-secondary');

            // layouts
            $this->registerComponentX('layouts.admin-form', 'admin-form');
            Blade::component('gotime::layouts.base', 'gotime-layouts.base');

            // table components
            $this->registerComponentX('table.th', 'table.th');

            // toolbars
            $this->registerComponentX('toolbar.search-sort-toolbar', 'search-sort-toolbar');
            $this->registerComponentX('toolbar.actions-toolbar', 'actions-toolbar');
            $this->registerComponentX('toolbar.title-bar', 'title-bar');

            // modals and alerts
            $this->registerComponentX('modal');
            $this->registerComponentX('modal.dialog');
            $this->registerComponentX('modal.save');
            $this->registerComponentX('modal.delete');
            $this->registerComponentX('modal.confirmation');
        });
    }

    protected function registerIconComponents()
    {
        $this->createComponentsFromDirectory('icon'); // base directory
        // $this->createComponentsFromDirectory('payment');
        // $this->createComponentsFromDirectory('logos');
    }

    protected function registerFormComponents(): void
    {
        $this->registerComponentX('form');
        // to be reviewed
        $this->registerComponentX('input-group.choices', 'choices');
        $this->registerComponentX('input.submit', 'submit');
        $this->registerComponentX('input.textarea', 'textarea');

        // Combined Controls
        $this->registerComponentX('input.checkbox', 'checkbox');
        $this->registerComponentX('input.email');
        $this->registerComponentX('input.file');
        $this->registerComponentX('input.input', 'input');
        $this->registerComponentX('input.number');
        $this->registerComponentX('input.password');
        $this->registerComponentX('input.select', 'select');

        // Other
        $this->registerComponentX('input.datepicker', 'datepicker');
        $this->registerComponentX('input.ckeditor', 'ckeditor');
        $this->registerComponentX('input.trix', 'trix');
        $this->registerComponentX('input.filepond', 'filepond');
    }



    /**
     * Loop through directory to create icon components
     */
    protected function createComponentsFromDirectory(string $dir = ''): void
    {

        $filesInFolder = \File::files(__dir__ . "/../resources/views/components/$dir");

        foreach ($filesInFolder as $path) {
            $fileInfo = pathinfo($path);

            // for reasons I do not understand you can not strip the period
            // from the components root directory so it needs to be removed
            // separately
            $component = rtrim($fileInfo['filename'], 'blade');
            $component = rtrim($component, '.');

            $this->registerIconComponent($component, $dir);
        }
    }


    protected function registerIconComponent(string $component, string $dir = '', string $prefix = 'gt-icon-'): void
    {
        if (!empty($dir)) {
            $dir = ".$dir";
        }

        Blade::component("gotime::components" . "$dir.$component", $prefix . $component);
    }


    /**
     * @param string $component (path and name)
     * @param string $alias use when component name and path are !=
     */
    protected function registerComponentX(string $component, string $alias = null): void
    {
        Blade::component('gotime::components.' . $component, 'gt-' . ($alias ?? $component));
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
