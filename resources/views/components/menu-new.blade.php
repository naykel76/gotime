<nav {{ $attributes }}>

    @foreach($menu->links as $item)

        @php
            $children = ($item->children ?? null);
            $url = $getUrl($item);
            $active = 'bg-grey-2';
        @endphp

        <a href="{{ url($url) }}" class="{{ request()->is($url) ? $active : '' }}">
            {{ $item->name }}
        </a>

        {{-- children only support url, not named route --}}
        @if($children)

            <div class="pl">

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

        @endif

    @endforeach

    {{ $slot }}

</nav>
