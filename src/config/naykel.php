<?php

return [
    
    'copyright' => env('NK_COPYRIGHT', 'NAYKEL'), // footer copyright company

    /**
     * ----------------------------------------------------------------------
     * Application Image Path
     * ----------------------------------------------------------------------
     *
     */
    'logo' => env('NK_LOGO', '/images/nk/logo-alt.svg'),
    'icon' => env('NK_ICON', '/images/nk/favicon.ico'),

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
     * Google Recapture Settings
     * ----------------------------------------------------------------------
     *
     */
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', '')
    ],


];
