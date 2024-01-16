@props([ 'controlOnly' => false, 'for' => null ])

@if($controlOnly)
    <textarea {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
    </textarea>
@else
    <x-gt-control-group>
        <textarea {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
    </textarea>
    </x-gt-control-group>
@endif
