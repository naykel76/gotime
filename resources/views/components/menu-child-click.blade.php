{{-- At first, I wasn't so sure and thought the menu-link component might be
more trouble than it's worth. However, I'm think it might be useful, when we
include options like icons and parent parameters. --}}

<nav {{ $attributes }}>

    @foreach($menu->links as $item)

        @php
            $children = ($item->children ?? null);
            $url = $getUrl($item);
            $active = $isActive($url);
        @endphp

        @unless($children)
            <x-gt-menu-link :$url :$active :$itemClass> {{ $item->name }} </x-gt-menu-link>
        @endunless

        @if($children)

            <div x-data="{ open: false }">

                <div x-on:click="open = !open">
                    <a href="#" class="space-between">
                        {{ $item->name }}
                        <x-gt-icon-down-caret x-cloak x-show="!open" />
                        <x-gt-icon-up-caret x-cloak x-show="open" />
                    </a>
                </div>

                <div x-show="open" class="pl" x-transition>

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

</nav>
