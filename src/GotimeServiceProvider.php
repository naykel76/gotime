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
                __DIR__ . '/../config/gotime.php' => config_path('gotime.php'),
            ], 'gotime-config');
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
        $this->registerComponent('loading-indicator');
        $this->registerComponent('spinner');
        $this->registerComponent('tooltip');

        $this->registerComponent('sidebar');
        // Alerts, Notifications, and Messages
        $this->registerComponent('alert');
        $this->registerComponent('errors');
        $this->registerComponent('toast');

        $this->registerComponent('accordion.base', 'accordion'); // yes, theses are the same
        $this->registerComponent('accordion.base', 'accordion.base'); // yes, theses are the same
        $this->registerComponent('box.base', 'box');

        // Modals and Boxes
        $this->registerComponent('modal.base', 'modal'); // yes, theses are the same
        $this->registerComponent('modal.base', 'modal.base'); // yes, theses are the same
        $this->registerComponent('modal.variants.confirm', 'modal.confirm');
        $this->registerComponent('modal.variants.delete', 'modal.delete');
        $this->registerComponent('modal.variants.dialog', 'modal.dialog');

        // Tables and Lists
        $this->registerComponent('table.table', 'table');
        $this->registerComponent('table.th', 'table.th');

        // toolbars
        $this->registerComponent('toolbar.title-bar', 'title-bar');
    }

    protected function registerFormComponents(): void
    {
        // buttons
        $this->registerComponent('button.action'); // shell only
        $this->registerComponent('button.base');
        $this->registerComponent('button.default', 'button');
        $this->registerComponent('button.primary');
        $this->registerComponent('button.secondary');
        $this->registerComponent('button.submit', 'submit');
        $this->registerComponent('resource-action'); // the works

        // inputs
        $this->registerComponent('input.checkbox', 'checkbox');
        $this->registerComponent('input.ckeditor', 'ckeditor');
        $this->registerComponent('input.email', 'input.email');
        $this->registerComponent('input.flatpickr', 'flatpickr');
        $this->registerComponent('input.input', 'input');
        $this->registerComponent('input.password', 'input.password');
        $this->registerComponent('input.pikaday', 'pikaday');
        $this->registerComponent('input.select', 'select');
        $this->registerComponent('input.slim-select', 'slim-select');
        $this->registerComponent('input.textarea', 'textarea');
        $this->registerComponent('input.toggle', 'toggle');
        $this->registerComponent('livewire-search-input', 'search-input');
        // $this->registerComponent('input.datepicker', 'datepicker');
        // $this->registerComponent('input.editor', 'editor');
        // $this->registerComponent('input.radio', 'radio');

    }

    /**
     * @param  string  $component  (path and name)
     * @param  string  $alias  use when component name and path are !=
     * @param  string  $prefix  (gtl for special livewire components)
     */
    protected function registerComponent(string $component, ?string $alias = null, string $prefix = 'gt'): void
    {
        Blade::component('gotime::components.' . $component, "$prefix-" . ($alias ?? $component));
    }
}
