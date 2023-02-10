<x-gt-sidebar class="secondary outline">

    <x-slot name="toggle">
        <button {{ $attributes->merge(['class' => 'btn pxy-05']) }} x-on:click="open = true">
            <x-gt-icon-menu class="icon" />
        </button>
    </x-slot>

    <x-gt-menu menuname="main" filename="nav-main" class="menu" :isNewMenuComponent=true />

    {{ $slot }}

</x-gt-sidebar>
