{{-- nav-item are nested inside the component style and therefor
can be different styles for different components.For example the
.navbar.nav-item or .menu.nav-item --}}

{{-- add props to component class --}}

<nav {{ $attributes }}>

    @foreach($menu->$menuname->links as $item)

        <?php
            // check if named route exists and not null
            if (isset($item->route_name) && Route::has($item->route_name) ?? isset($item->url)) {
                $link = route($item->route_name);
                $active = request()->routeIs("$item->route_name*");
            } elseif(isset($item->url)){
                $link = url($item->url);
                $active = request()->is($item->url);
            }

            $children = ($item->children ?? null);
        ?>

        <x-gotime::menu-item href="{{ $link }}" :active=$active :children=$children class="{{ $itemClass }}">

            @if($useIcons)
                @isset($item->icon)
                    <x-gotime-icon icon="{{ $item->icon }}" />
                @endisset
            @endif

            <span>{{ $item->title }}</span>

        </x-gotime::menu-item>

    @endforeach

    {{ $slot }}

</nav>
