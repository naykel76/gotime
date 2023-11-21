@props([ 'text', 'withIcon' => false])

{{--
    at first glance it does not make sense to set the `text` value here, but
    this allow us to omit the text value for icon only buttons
--}}

@if(!isset($text) && $withIcon)

    @php
        $text = ''
    @endphp

@endif

<x-gotime::button-v2.control-button {{ $attributes->merge(['type' => 'submit']) }} text="{{ $text ?? 'submit' }}" />
