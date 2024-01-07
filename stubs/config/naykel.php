<?php

return [

    /**
     * ----------------------------------------------------------------------
     * Basic Settings
     * ----------------------------------------------------------------------
     *
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
     * app.blade, bare-bones.php
     */
    'template' => env('NK_DEFAULT_TEMPLATE', 'app'),

    /**
     * ----------------------------------------------------------------------
     * Contact Page (contactit package)
     * ----------------------------------------------------------------------
     * if false, prevents 'contact' route creation. Used on applications where
     * there is no contact landing page. For example a spa with contact
     * component on the front page.
     */

    'has_contact_page' => env('NK_HAS_CONTACT_PAGE', true), // used to enable/disable contact page routes

];
