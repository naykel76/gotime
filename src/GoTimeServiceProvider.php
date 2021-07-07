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


        /** Publish ALL Required Assets 
         * ==================================================================
         * these are assets that are required to make the application work
         * some of these resources can be republished in other areas below
         */
        $this->publishes(
            [
                __DIR__ . '/../public' => public_path(''),
                __DIR__ . '/../resources/js' => resource_path('js'),
                __DIR__ . '/../resources/scss' => resource_path('scss'),
                __DIR__ . '/../resources/views/navs/nav-main.json' => resource_path('views/navs/nav-main.json'),
                __DIR__ . '/../resources/views/pages/home.blade.php' => resource_path('views/pages/home.blade.php'),
                __DIR__ . '/config/naykel.php' => './config/naykel.php',
                __DIR__ . '/routes.php' => './routes/web.php',
                __DIR__ . '/webpack.mix.js' => './webpack.mix.js',
            ],
            'nkr'
        );

         /** Publish SOME Required Assets 
         * ==================================================================
         * These are the assets that are likely to change but excludes assets
         * required for the initial install that are not likely to change.
         */
        $this->publishes(
            [
                // __DIR__ . '/../resources/js' => resource_path('js'),
                __DIR__ . '/config/naykel.php' => './config/naykel.php',
                // __DIR__ . '/routes.php' => './routes/web.php',
                __DIR__ . '/webpack.mix.js' => './webpack.mix.js',
            ],
            'gotime-config'
        );

        /** Publish Views (optional) 
         * ==================================================================
         * 
         */
        $this->publishes(
            [
                __DIR__ . '/../resources/views/pages/' => resource_path('views/pages/'),
            ],
            'gotime-views'
        );
    }
}
