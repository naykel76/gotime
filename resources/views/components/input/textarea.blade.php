@props([ 'controlOnly' => false, 'for' => null ])

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
