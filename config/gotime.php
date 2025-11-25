<?php

return [

    /**
     * ----------------------------------------------------------------------
     * Basic Settings
     * ----------------------------------------------------------------------
     */
    'logo' => [
        'path' => env('NK_LOGO_PATH', '/logo.svg'),
        'height' => env('NK_LOGO_HEIGHT', ''),
        'width' => env('NK_LOGO_WIDTH', ''),
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
    'date_format' => env('NK_DATE_FORMAT', 'd-m-Y'),

    /**
     * ----------------------------------------------------------------------
     * Date Format Mappings
     * ----------------------------------------------------------------------
     * This is the mapping of date formats for different date picker libraries.
     * It is used to ensure that the date format is consistent across the
     * application and that the date pickers are configured correctly.
     */
    'date_format_mappings' => [
        'd-m-y' => [ // Output: 05-03-25
            'pikaday' => 'DD-MM-YY',
            'flatpickr' => 'd-m-y',
        ],
        'd-m-Y' => [ // Output: 05-03-2025
            'pikaday' => 'DD-MM-YYYY',
            'flatpickr' => 'd-m-Y',
        ],
        'M d, Y' => [ // Output: Mar 05, 2025
            'pikaday' => 'MMM DD, YYYY',
            'flatpickr' => 'M d, Y',
        ],
        'd M, Y' => [ // Output: 05 Mar, 2025
            'pikaday' => 'DD MMM, YYYY',
            'flatpickr' => 'd M, Y',
        ],
        'd-M-Y' => [ // Output: 05-Mar-2025
            'pikaday' => 'DD-MMM-YYYY',
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
     * Component Settings
     * ----------------------------------------------------------------------
     * These values are the default settings for the components.
     */
    'component' => [
        'icon' => [
            'type' => env('NK_ICON_STYLE', 'outline'),
        ],
    ],

];
