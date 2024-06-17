<x-gotime::menu.base {{ $attributes }}>
    @foreach ($menuItems as $item)
        @php
            $children = $item->children ?? null;
            $url = $item->url;
            $active = $isActive($url);
        @endphp

        @unless ($children)
            <li>
                <x-gotime::menu.menu-link :$url :$active :$itemClass :$newWindow>
                    {{ $item->name }}
                </x-gotime::menu.menu-link>
            </li>
        @endunless

        @if ($children)
            <li class="relative" x-data="{ show: false }" x-on:mouseenter="show=true" x-on:mouseleave="show=false">
                <x-gotime::menu.menu-link :$url :$active :$itemClass :$newWindow :isParent="$item->isParent">
                    {{ $item->name }}
                </x-gotime::menu.menu-link>
                <div class="absolute mt-05 flex w-16 z-100" x-show="show" x-transition.duration style="display: none;">
                    <div class="menu bx pxy-0 w-full">
                        <x-gotime::menu.menu-children :children="$item->children" />
                    </div>
                </div>
            </li>
        @endif
    @endforeach
    {{ $slot }}
</x-gotime::menu.base>
