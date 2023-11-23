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

{{-- do not use control layout here, just style manually when the component is used --}}
<x-gotime::button-v2.control-button {{ $attributes->merge(['type' => 'button']) }} text="{{ $text ?? 'Button' }}">
    {{ $slot }}
</x-gotime::button-v2.control-button>
