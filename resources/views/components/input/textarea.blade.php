@props([ 'controlOnly' => false, 'for' => null ])

@if($controlOnly)
    <textarea {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
    </textarea>
@else
    <x-gotime::v2.input.layout-control-group>
        <textarea {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
    </textarea>
    </x-gotime::v2.input.layout-control-group>
@endif
