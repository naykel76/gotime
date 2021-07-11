<?php

return [

    /**
     * ----------------------------------------------------------------------
     * Account Features
     * ----------------------------------------------------------------------
     *
     * Fortify routes are created based on the Fortify features array. This 
     * account array is used to show or hide elements throughout the site.
     * 
     */
    'account' => [
        'register' => env('NK_ALLOW_REGISTER', true), // show or hide login/register buttons
    ],


    /**
     * ----------------------------------------------------------------------
     * Basic Settings
     * ----------------------------------------------------------------------
     *
     */

    'logo' => [
        'path' => env('NK_LOGO_PATH', '/images/nk/logo-alt.svg'),
        'height' => env('NK_LOGO_HEIGHT', ''),
    ],


    'icon' => env('NK_ICON', '/images/nk/favicon.ico'),
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
     * Google Recapture Settings
     * ----------------------------------------------------------------------
     *
     */
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', '')
    ],


];
