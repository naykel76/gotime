@props([ 'controlOnly' => false, 'for' => null, 'placeholder' => null ])

{{-- NK::TD this needs to be set up to work with laravel values --}}
{{-- @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif --}}

@if($controlOnly)
    <textarea {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
    </textarea>
@else
    <x-gotime::input.control-group-layout>
        <textarea {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
    </textarea>
    </x-gotime::input.control-group-layout>
@endif
