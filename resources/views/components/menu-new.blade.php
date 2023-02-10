<nav {{ $attributes }}>

    @foreach($menu->links as $item)

        @php
            $children = ($item->children ?? null);
            $url = $getUrl($item);
            $active = 'bg-grey-2';
        @endphp

        @unless($children)
            <a href="{{ url($url) }}" class="{{ request()->is($url) ? $active : '' }}"> {{ $item->name }} </a>
        @endunless

        {{-- children only support url, not named route --}}
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
