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

        @if(!isset($link))
            {{ dd( "The '$item->name' item ,from the `$menuname` object, in '$filename.json' is missing a route or url. \nFix it and your day will get better!.") }}
        @endif

        <x-gotime::menu-item href="{{ $link }}" :active=$active :children=$children class="{{ $itemClass }}">

            @if($useIconit)
                <x-dynamic-component :component="'gt-icon-'.$item->iconit" class="icon" />
            @endif

            @if($useIcons)
                @isset($item->icon)
                    <x-gotime-icon icon="{{ $item->icon }}" />
                @endisset
            @endif

            <span>{{ $item->name ?? $item->name}}</span>

        </x-gotime::menu-item>

    @endforeach

    {{ $slot }}

</nav>
