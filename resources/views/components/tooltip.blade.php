{{--
The `position` attribute has been created for easy positioning when used with
inputs. This may not work for all use cases but it ticks the box for now!
--}}

@props(['position'])

@php
    $position = [
    'center' => 'va-c',
    "top" => "va-t",
    "bottom" => "va-b",
    ][$position ?? 'center']
@endphp

<span x-data="{open:false}" class="relative"
    x-on:mouseenter="open=true" x-on:mouseleave="open=false">

    <div class="flex {{ $position }}">
        <x-gt-icon name="question-mark-circle" class="txt-muted icon" />
    </div>

    <div class="absolute pos-r minw-18 z-100 bx pxy-075 mt-05 txt-sm fw4 bg-stone-100" x-show="open"
        x-transition.duration style="display: none;">
        {{ $slot }}
    </div>

</span>
