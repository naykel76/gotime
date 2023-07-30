<x-gotime::menu-layout {{ $attributes }}>

    @foreach($menu->links as $item)

        @php
            $children = ($item->children ?? null);
            $url = $getUrl($item);
            $active = $isActive($url);
        @endphp

        @unless($children)

            <a href="{{ url($url) }}" @class(['active'=> $active, $itemClass ])
                @if($newWindow) target="_blank" @endif >
                    <x-gotime::menu-icon-selector :$withIcons :$item :$iconClass />
                    <span> {{ $item->name }} </span>
            </a>

        @endunless

        @if($children)

            <div x-data="{ open: false }">

                <div x-on:click="open = !open">

                    <a href="#" class="space-between">
                        <span>
                            <x-gotime::menu-icon-selector :$withIcons :$item :$iconClass />
                            {{ $item->name }}
                        </span>
                        <x-gt-icon-caret-down x-cloak x-show="!open" />
                        <x-gt-icon-caret-up x-cloak x-show="open" />
                    </a>
                </div>

                <div x-show="open" class="pl" x-transition x-cloak>

                    @foreach($children as $child)

                        @php
                            $children = ($item->children ?? null);
                            $childUrl = ltrim($child->url, '/');
                        @endphp

                        <a href="{{ url($childUrl) }}" class="{{ request()->is($childUrl) ? $active : '' }}">
                            {{ $child->name }}
                        </a>

                    @endforeach

                </div>

            </div>

        @endif

    @endforeach

    {{ $slot }}

</x-gotime::menu-layout>
