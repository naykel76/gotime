{{-- Define `includeOrder` property here to simplify declaring the attribute
directly on the component without needing to explicitly set it to true --}}
@props(['includeOrder' => false])

<x-gotime::menu.base {{ $attributes }}>

    @foreach ($menuItems as $item)
        @php
            $children = $item->children ?? null;
            $url = $item->url;
            $active = $isActive($url);
            $icon = $item->icon;
        @endphp

        @unless ($children)
            <x-gt-menu-item :order="$includeOrder ? $loop->index : null">
                <x-gt-menu-link :$url :itemClass="$itemClass" :newWindow="$newWindow">
                    <x-gotime::menu.icon-selector :withIcons="$withIcons" :icon="$icon" :iconClass="$iconClass" />
                    {{ $item->name }}
                </x-gt-menu-link>
            </x-gt-menu-item>
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
