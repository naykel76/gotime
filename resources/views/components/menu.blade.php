<nav {{ $attributes }}>

    @foreach($menu->links as $link)

        @php
            $children = ($link->children ?? null);
        @endphp

        <x-gotime::menu-item href="{{ $getUrl($link) }}" :active=$isActive($link) :$children class="{{ $itemClass }}">

            @if($withIcons && isset($link->icon))
                <x-dynamic-component :component="'gt-icon-'.$link->icon" class="icon {{ $iconClass }}" />
            @endif

            <span>{{ $link->name ?? $link->name }}</span>

        </x-gotime::menu-item>

    @endforeach

    {{ $slot }}

</nav>
