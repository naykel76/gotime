<?php

return [

    /**
     * ----------------------------------------------------------------------
     * Basic Settings
     * ----------------------------------------------------------------------
     */
    'logo' => [
        'path' => env('NK_LOGO_PATH', '/logo.svg'),
        'height' => env('NK_LOGO_HEIGHT', ''),  // don't set default
        'width' => env('NK_LOGO_WIDTH', ''),    // don't set default
        'sidebar_path' => env('NK_LOGO_SIDEBAR_PATH', '/logo.svg'),
        'sidebar_height' => env('NK_LOGO_HEIGHT', '40'),
    ],

    'favicon' => env('NK_FAVICON', '/favicon.svg'),
    'copyright' => env('NK_COPYRIGHT', 'NAYKEL'), // footer copyright company

    /**
     * ----------------------------------------------------------------------
     * Application Template
     * ----------------------------------------------------------------------
     * This value is the name of the default blade template for the application.
     * app.blade, app-header.php
     */
    'template' => env('NK_DEFAULT_TEMPLATE', 'app'),

    /**
     * ----------------------------------------------------------------------
     * Component Settings
     * ----------------------------------------------------------------------
     * These values are the default settings for the components.
     */
    'component' => [
        'icon' => [
            'type' => env('NK_ICON_STYLE', 'outline'),
        ],
    ],

    /**
     *--------------------------------------------------------------------------
     * Livewire Layout
     *--------------------------------------------------------------------------
     * Sets the default layout used in full-page Livewire components.
     *
     * This is not the same as setting the layout in the Livewire config. It
     * sets the `layout` property in the Gotime AppLayout component.
     * 
     * A typical use case would be to set the layout to 'admin' or 'app'
     * depending on having more pages in one or the other.
     */

    'livewire_layout' => env('NK_LIVEWIRE_LAYOUT', 'app'),

];
