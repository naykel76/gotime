<x-gotime::menu.base {{ $attributes }}>

    @foreach ($menuItems as $item)
        @php
            $children = $item->children ?? null;
            $url = $item->url;
            $active = $isActive($url);
            $icon = $item->icon;
        @endphp

        @unless ($children)
            <li>
                <x-gotime::menu.menu-link :$url :$active :$itemClass :$newWindow>
                    <x-gotime::menu.icon-selector :$withIcons :$icon :$iconClass />
                    {{ $item->name }}
                </x-gotime::menu.menu-link>
            </li>
        @endunless

        @if ($children)
            <div x-data="{ open: false }">
                <div x-on:click="open = !open">
                    <a href="#" class="space-between">
                        <span>
                            <x-gotime::menu.icon-selector :$withIcons :$icon :$iconClass />
                            {{ $item->name }}
                        </span>
                        <x-gt-icon name="chevron-down" class="wh-1" x-cloak x-show="!open" />
                        <x-gt-icon name="chevron-up" class="wh-1" x-cloak x-show="open" />
                    </a>
                </div>
                <div x-show="open" class="pl" x-transition x-cloak>
                    <x-gotime::menu.menu-children :$children />
                </div>
            </div>
        @endif
    @endforeach

    {{ $slot }}

</x-gotime::menu.base>
