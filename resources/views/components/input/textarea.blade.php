@props(['controlOnly' => false, 'for' => null])

@if ($controlOnly)
    <textarea {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red' => $errors->has($for)]) }}>
    </textarea>
@else
    <x-gotime::input.partials.control-group>
        <textarea {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has($for)]) }}> </textarea>
    </x-gotime::input.partials.control-group>
@endif
