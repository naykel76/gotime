
@props(['maxWidth', 'maxWidth' => '1200px', ])

    <div x-data="{show: @entangle($attributes->wire('model')).defer}" x-show="show" x-cloak
        x-on:click="show = false"
        x-on:keydown.escape.window="show = false" class="overlay va-c">

        <div style="width: min({{ $maxWidth }}, 90%)">

            <div class="tar mb">
                <x-gt-icon-cross x-on:click="show = false" class="light round close" />

            </div>

            {{ $slot }}

        </div>




    </div>

