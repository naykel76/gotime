<?php

namespace Naykel\GoTime;

use Illuminate\Support\ServiceProvider;
use Naykel\GoTime\View\Components\ActionsToolbar;
use Naykel\GoTime\View\Components\ImagePicker;
use Naykel\GoTime\View\Components\Menu;

class GoTimeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gotime');

        /** Load Package Components
         * ==================================================================
         * 
         */
        $this->loadViewComponentsAs('gotime', [
            ActionsToolbar::class,
            ImagePicker::class,
            Menu::class,
        ]);


        /** Stubs and Configuration Files
         * ==================================================================
         * Publish with the --force flag after initial install only.
         * 
         */
        $this->publishes(
            [
                __DIR__ . '/Providers' => app_path('Providers'),
                __DIR__ . '/config/naykel.php' => './config/naykel.php',
            ],
            'nkr-stubs'
        );

        /** Required Assets
         * ==================================================================
         * 
         */
        $this->publishes(
            [
                __DIR__ . '/../public' => public_path(''),
                __DIR__ . '/../resources/js' => resource_path('js'),
                __DIR__ . '/../resources/navs' => resource_path('navs'),
                __DIR__ . '/../resources/scss' => resource_path('scss'),
                __DIR__ . '/../resources/views/pages/' => resource_path('views/pages/'),
                __DIR__ . '/routes.php' => './routes/web.php',
                __DIR__ . '/webpack.mix.js' => './webpack.mix.js',
            ],
            'nkr'
        );
    }
}
