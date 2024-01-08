@props([ 'text', 'withIcon' => false])

{{-- at first glance it may not make sense to set the `text` value here, but
this allow us to omit the text value for icon only buttons --}}

@if(!isset($text) && $withIcon)
    @php
        $text = ''
    @endphp
@endif

{{-- do not use control layout here, just style manually when the component is used --}}
<x-gotime::v2.button.control-button {{ $attributes->merge(['type' => 'submit']) }} text="{{ $text ?? 'Submit' }}">
{{ $slot }}
</x-gotime::v2.button.control-button>
