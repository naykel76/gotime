<div {{ $attributes->class(['overlay flex ha-c va-c mxy-0']) }}>
    <div class="la-ball-spin la-2x">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

    @push('styles')
    <style>
    /*!
     * Load Awesome v1.1.0 (http://github.danielcardoso.net/load-awesome/)
     * Copyright 2015 Daniel Cardoso <@DanielCardoso>
     * Licensed under MIT
     */
    .la-ball-spin,
    .la-ball-spin > div {
        position: relative;
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
    }
    .la-ball-spin {
        display: block;
        font-size: 0;
        color: #fff;
    }
    .la-ball-spin.la-dark {
        color: #333;
    }
    .la-ball-spin > div {
        display: inline-block;
        float: none;
        background-color: currentColor;
        border: 0 solid currentColor;
    }
    .la-ball-spin {
        width: 32px;
        height: 32px;
    }
    .la-ball-spin > div {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 8px;
        height: 8px;
        margin-top: -4px;
        margin-left: -4px;
        border-radius: 100%;
        -webkit-animation: ball-spin 1s infinite ease-in-out;
           -moz-animation: ball-spin 1s infinite ease-in-out;
             -o-animation: ball-spin 1s infinite ease-in-out;
                animation: ball-spin 1s infinite ease-in-out;
    }
    .la-ball-spin > div:nth-child(1) {
        top: 5%;
        left: 50%;
        -webkit-animation-delay: -1.125s;
           -moz-animation-delay: -1.125s;
             -o-animation-delay: -1.125s;
                animation-delay: -1.125s;
    }
    .la-ball-spin > div:nth-child(2) {
        top: 18.1801948466%;
        left: 81.8198051534%;
        -webkit-animation-delay: -1.25s;
           -moz-animation-delay: -1.25s;
             -o-animation-delay: -1.25s;
                animation-delay: -1.25s;
    }
    .la-ball-spin > div:nth-child(3) {
        top: 50%;
        left: 95%;
        -webkit-animation-delay: -1.375s;
           -moz-animation-delay: -1.375s;
             -o-animation-delay: -1.375s;
                animation-delay: -1.375s;
    }
    .la-ball-spin > div:nth-child(4) {
        top: 81.8198051534%;
        left: 81.8198051534%;
        -webkit-animation-delay: -1.5s;
           -moz-animation-delay: -1.5s;
             -o-animation-delay: -1.5s;
                animation-delay: -1.5s;
    }
    .la-ball-spin > div:nth-child(5) {
        top: 94.9999999966%;
        left: 50.0000000005%;
        -webkit-animation-delay: -1.625s;
           -moz-animation-delay: -1.625s;
             -o-animation-delay: -1.625s;
                animation-delay: -1.625s;
    }
    .la-ball-spin > div:nth-child(6) {
        top: 81.8198046966%;
        left: 18.1801949248%;
        -webkit-animation-delay: -1.75s;
           -moz-animation-delay: -1.75s;
             -o-animation-delay: -1.75s;
                animation-delay: -1.75s;
    }
    .la-ball-spin > div:nth-child(7) {
        top: 49.9999750815%;
        left: 5.0000051215%;
        -webkit-animation-delay: -1.875s;
           -moz-animation-delay: -1.875s;
             -o-animation-delay: -1.875s;
                animation-delay: -1.875s;
    }
    .la-ball-spin > div:nth-child(8) {
        top: 18.179464974%;
        left: 18.1803700518%;
        -webkit-animation-delay: -2s;
           -moz-animation-delay: -2s;
             -o-animation-delay: -2s;
                animation-delay: -2s;
    }
    .la-ball-spin.la-sm {
        width: 16px;
        height: 16px;
    }
    .la-ball-spin.la-sm > div {
        width: 4px;
        height: 4px;
        margin-top: -2px;
        margin-left: -2px;
    }
    .la-ball-spin.la-2x {
        width: 64px;
        height: 64px;
    }
    .la-ball-spin.la-2x > div {
        width: 16px;
        height: 16px;
        margin-top: -8px;
        margin-left: -8px;
    }
    .la-ball-spin.la-3x {
        width: 96px;
        height: 96px;
    }
    .la-ball-spin.la-3x > div {
        width: 24px;
        height: 24px;
        margin-top: -12px;
        margin-left: -12px;
    }
    /*
     * Animation
     */
    @-webkit-keyframes ball-spin {
        0%,
        100% {
            opacity: 1;
            -webkit-transform: scale(1);
                    transform: scale(1);
        }
        20% {
            opacity: 1;
        }
        80% {
            opacity: 0;
            -webkit-transform: scale(0);
                    transform: scale(0);
        }
    }
    @-moz-keyframes ball-spin {
        0%,
        100% {
            opacity: 1;
            -moz-transform: scale(1);
                 transform: scale(1);
        }
        20% {
            opacity: 1;
        }
        80% {
            opacity: 0;
            -moz-transform: scale(0);
                 transform: scale(0);
        }
    }
    @-o-keyframes ball-spin {
        0%,
        100% {
            opacity: 1;
            -o-transform: scale(1);
               transform: scale(1);
        }
        20% {
            opacity: 1;
        }
        80% {
            opacity: 0;
            -o-transform: scale(0);
               transform: scale(0);
        }
    }
    @keyframes ball-spin {
        0%,
        100% {
            opacity: 1;
            -webkit-transform: scale(1);
               -moz-transform: scale(1);
                 -o-transform: scale(1);
                    transform: scale(1);
        }
        20% {
            opacity: 1;
        }
        80% {
            opacity: 0;
            -webkit-transform: scale(0);
               -moz-transform: scale(0);
                 -o-transform: scale(0);
                    transform: scale(0);
        }
    }
    </style>
    @endpush
