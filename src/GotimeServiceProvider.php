<?php

namespace Naykel\Gotime;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Naykel\Gotime\View\Components\ActionsToolbar;
use Naykel\Gotime\View\Components\Menu;
use Naykel\Gotime\View\Components\Parsedown;
use Naykel\Gotime\View\Layouts\AppLayout;


class GotimeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('filesys', function ($app) {
            return new Filesys();
        });

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
        $this->commands([InstallCommand::class,]);


        /** Load Package Components
         * ==================================================================
         *
         */
        $this->loadViewComponentsAs('gotime', [
            AppLayout::class,
            ActionsToolbar::class,
            Menu::class,
            Parsedown::class,
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
        ], 'gotime-partials');

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
            $this->registerComponent('icon');
            $this->registerComponent('errors');

            // Notifications, Flash and Messages

            $this->registerComponent('notification-toaster');
            $this->registerComponent('message');

            // buttons
            $this->registerComponentX('button.button', 'button');
            $this->registerComponentX('button.quick-add');
            $this->registerComponentX('button.add', 'button-add');
            $this->registerComponentX('button.save', 'button-save');
            $this->registerComponentX('button.primary', 'button-primary');
            $this->registerComponentX('button.secondary', 'button-secondary');
            $this->registerComponentX('button.delete', 'button-delete');

            // table components
            $this->registerComponentX('table.th', 'table.th');

            // toolbars
            $this->registerComponentX('toolbar.search-sort-toolbar', 'search-sort-toolbar');

            // modals and alerts
            $this->registerComponentX('modal');
            $this->registerComponentX('modal.dialog');
            $this->registerComponentX('modal.save');
            $this->registerComponentX('modal.confirmation');

            $this->registerComponentX('alert');

            $this->registerComponentX('loading-indicator');

            $this->registerFormComponents();

            Blade::component('gotime::layouts.base', 'gotime-layouts.base');
        });
    }

    /**

     *
     * @return void
     */
    protected function registerFormComponents()
    {
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
     * Register the given component.
     *
     * @param string $component (path and name)
     * @param string $alias use when component name and path are !=
     * @return void
     */
    protected function registerComponentX(string $component, string $alias = null)
    {
        // !-- STOP CHANGING YOUR MIND --!
        // USE the gt- prefix for ALL updated components
        // ???????? if alias null use component
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
