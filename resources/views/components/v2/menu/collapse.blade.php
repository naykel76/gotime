<x-gotime::v2.menu.base {{ $attributes }}>
    @foreach ($menuItems as $item)
        @php
            $children = $item->children ?? null;
            $url = $item->url;
            $active = $isActive($url);
            $icon = $item->icon;
        @endphp
        @unless ($children)
            <li>
                <a href="{{ url($url) }}" @class(['menu-link', 'active' => $active, $itemClass])>
                    @if ($withIcons && $icon !== '')
                        <x-gt-icon name="{{ $icon }}" />
                    @endif
                    {{ $item->name }}
                </a>
            </li>
        @endunless

        @if ($children)
            <li x-data="{ open: {{ $open ? 'true' : 'false' }} }">
                <button x-on:click="open = ! open" @class(['active' => $active, $itemClass])>
                    @if ($withIcons && $icon !== '')
                        <span>
                            <x-gt-icon name="{{ $icon }}" />
                            {{ $item->name }}
                        </span>
                    @else
                        {{ $item->name }}
                    @endif
                    <svg class="wh-1" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path x-show="open" d="m6 15 6-6 6 6" />
                        <path x-show="!open" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <!-- child menu -->
                <ul x-show="open" x-collapse>
                    @foreach ($children as $child)
                        @php
                            $childActive = $isActive($child->url);
                        @endphp
                        <li>
                            <a href="{{ url($child->url) }}" @class(['menu-link', 'active' => $childActive, $itemClass])>
                                {{ $child->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endforeach
</x-gotime::v2.menu.base>
