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
     * Date Format
     * ----------------------------------------------------------------------
     * This is the default date format for the application. It is used in the
     * DateCast class and date pickers components to ensure that dates are
     * formatted correctly.
     */
    'date_format' => env('NK_DATE_FORMAT', 'd-M-Y'), // 28-MAR-2024

    // Date format mappings for Pikaday and Flatpickr
    'date_format_mappings' => [
        'd-m-y' => [
            'pikaday' => 'DD-MM-YY',
            'flatpickr' => 'd-m-y',
        ],
        'd-m-Y' => [
            'pikaday' => 'DD-MM-YYYY',
            'flatpickr' => 'd-m-Y',
        ],
        'M d, Y' => [
            'pikaday' => 'MMM D, YYYY',
            'flatpickr' => 'M d, Y',
        ],
        'd M, Y' => [
            'pikaday' => 'D MMM, YYYY',
            'flatpickr' => 'd M, Y',
        ],
        'd-M-Y' => [
            'pikaday' => 'D-MMM-YYYY',
            'flatpickr' => 'd-M-Y',
        ],
    ],

    /**
     * ----------------------------------------------------------------------
     * Enable Debug Mode
     * ----------------------------------------------------------------------
     * This is not the same as Laravel's debug mode. This is a custom debug mode
     * that can be used to display debug information in the application such as
     * ID's, keys, etc.
     */
    'debug' => env('NK_DEBUG', true),

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
