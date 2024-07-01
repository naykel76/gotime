<x-gt-button.base {{ $attributes->merge(['class' => 'btn']) }} />



{{-- <button {{ $attributes->merge(['class' => 'btn']) }}> --}}

{{-- @props(['text', 'withIcon' => false]) --}}

{{-- at first glance it may not make sense to set the `text` value here, but
this allow us to omit the text value for icon only buttons --}}



{{-- do not use control layout here, just style manually when the component is used --}}
{{-- <x-gotime::button.control-button {{ $attributes->merge(['type' => 'button']) }} text="{{ $text ?? 'Click' }}">
    {{ $slot }}
</x-gotime::button.control-button> --}}


{{-- @props(['text', 'withIcon' => false]) --}}

{{-- at first glance it may not make sense to set the `text` value here, but
this allow us to omit the text value for icon only buttons --}}

{{-- @if (!isset($text) && $withIcon)
    @php
        $text = '';
    @endphp
@endif --}}

{{-- do not use control layout here, just style manually when the component is used --}}
{{-- <x-gotime::button.control-button {{ $attributes->merge(['type' => 'button']) }} text="{{ $text ?? 'Click' }}">
    {{ $slot }}
</x-gotime::button.control-button> --}}
