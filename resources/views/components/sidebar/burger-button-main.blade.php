<x-gt-sidebar class="secondary outline">

    <x-slot name="toggle">
        <button {{ $attributes->merge(['class' => 'btn pxy-05']) }} x-on:click="open = true">
            <x-gt-icon name="bars-3" />
        </button>
    </x-slot>

    @isset($top)
        {{ $top }}
    @endisset

    {{-- NK::FIX --}}
    <x-gt-menu layout="click" class="menu" />

    {{ $slot }}

</x-gt-sidebar>
