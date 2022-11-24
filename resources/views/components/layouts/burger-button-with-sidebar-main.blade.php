@props(['btnClass' => 'secondary'])

<div {{ $attributes->merge(['class' => 'mxy-0']) }}>

    <x-gotime::button.burger-menu class="{{ $btnClass }}" />

</div>

<x:gotime::sidebar.main>

    {{ $slot }}

</x:gotime::sidebar.main>
