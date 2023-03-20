<x-gt-sidebar class="secondary outline">

    <x-slot name="toggle">
        <button {{ $attributes->merge(['class' => 'btn pxy-05']) }} x-on:click="open = true">
            <x-gt-icon-menu class="icon" />
        </button>
    </x-slot>

    @isset($top)
        {{ $top }}
    @endisset

    <x-gt-menu layout="click" class="menu" />

    {{ $slot }}

</x-gt-sidebar>
