<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Gotime\Commands\InstallCommand;
use Naykel\Gotime\Components\Filepond;
use Naykel\Gotime\Components\Icon;
use Naykel\Gotime\Components\Markdown;
use Naykel\Gotime\Components\Nav;

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
        $this->registerComponents();
        $this->registerDirectives();
        $this->registerFormComponents();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        $this->loadViewComponentsAs('gt', [
            Filepond::class,
            Icon::class,
            Markdown::class,
            Nav::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([InstallCommand::class]);

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
        }
    }

    protected function registerDirectives(): void
    {
        // Render slot in a <div> with optional attributes
        Blade::directive('addSlot', function ($expression) {
            $parts = array_map('trim', explode(',', $expression, 2));
            $slot = $parts[0];
            $attrs = $parts[1] ?? '[]';

            return <<<BLADE
            <?php if(isset({$slot}) && {$slot}->isNotEmpty()): ?>
                <div {{ {$slot}->attributes->merge({$attrs}) }}>
                    {{ {$slot} }}
                </div>
            <?php endif; ?>
            BLADE;
        });

        // Render slot in a custom element with optional attributes
        Blade::directive('addSlotEl', function ($expression) {
            $parts = array_map('trim', explode(',', $expression, 3));
            $tag = $parts[0];
            $slot = $parts[1];
            $attrs = $parts[2] ?? '[]';

            return <<<BLADE
            <?php if(isset({$slot}) && {$slot}->isNotEmpty()): ?>
                <<?php echo {$tag}; ?> {{ {$slot}->attributes->merge({$attrs}) }}>
                    {{ {$slot} }}
                </<?php echo {$tag}; ?>>
            <?php endif; ?>
            BLADE;
        });
    }

    protected function registerComponents()
    {
        $this->registerComponentX('loading-indicator');
        $this->registerComponentX('spinner');
        $this->registerComponentX('tooltip');

        $this->registerComponentX('sidebar');
        // Alerts, Notifications, and Messages
        $this->registerComponentX('alert');
        $this->registerComponentX('errors');
        $this->registerComponentX('toast');

        $this->registerComponentX('accordion.base', 'accordion');
        $this->registerComponentX('box.base', 'box');

        // Modals and Boxes
        $this->registerComponentX('modal.base', 'modal'); // yes, theses are the same
        $this->registerComponentX('modal.base', 'modal.base'); // yes, theses are the same
        $this->registerComponentX('modal.variants.confirm', 'modal.confirm');
        $this->registerComponentX('modal.variants.delete', 'modal.delete');
        $this->registerComponentX('modal.variants.dialog', 'modal.dialog');

        // Tables and Lists
        $this->registerComponentX('table.table', 'table');
        $this->registerComponentX('table.th', 'table.th');

        // toolbars
        $this->registerComponentX('toolbar.title-bar', 'title-bar');
    }

    protected function registerFormComponents(): void
    {
        // buttons
        $this->registerComponentX('button.action'); // shell only
        $this->registerComponentX('button.base');
        $this->registerComponentX('button.default', 'button');
        $this->registerComponentX('button.primary');
        $this->registerComponentX('button.secondary');
        $this->registerComponentX('button.submit', 'submit');
        $this->registerComponentX('resource-action'); // the works

        // inputs
        $this->registerComponentX('input.checkbox', 'checkbox');
        $this->registerComponentX('input.ckeditor', 'ckeditor');
        $this->registerComponentX('input.email', 'input.email');
        $this->registerComponentX('input.flatpickr', 'flatpickr');
        $this->registerComponentX('input.input', 'input');
        $this->registerComponentX('input.password', 'input.password');
        $this->registerComponentX('input.pikaday', 'pikaday');
        $this->registerComponentX('input.select', 'select');
        $this->registerComponentX('input.slim-select', 'slim-select');
        $this->registerComponentX('input.textarea', 'textarea');
        $this->registerComponentX('input.toggle', 'toggle');
        $this->registerComponentX('livewire-search-input', 'search-input');
        // $this->registerComponentX('input.datepicker', 'datepicker');
        // $this->registerComponentX('input.editor', 'editor');
        // $this->registerComponentX('input.radio', 'radio');

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
