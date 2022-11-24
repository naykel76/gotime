
@props(['maxWidth', 'maxWidth' => '1200px', ])

    <div x-data="{show: @entangle($attributes->wire('model')).defer}" x-show="show" x-cloak
        x-on:click="show = false"
        x-on:keydown.escape.window="show = false" class="overlay va-c">

        {{-- <svg class="icon txt-white wh-2">
            <use href="/svg/naykel-ui-SVG-sprite.svg#left-caret"></use>
        </svg> --}}

        <div style="width: min({{ $maxWidth }}, 90%)">

            <div class="tar mb">
                <x-gotime-icon x-on:click="show = false" icon="close" class="light round close" />
            </div>

            {{ $slot }}

        </div>




    </div>

