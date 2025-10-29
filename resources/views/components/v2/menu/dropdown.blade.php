<nav {{ $attributes }}>
    {{-- ✅ active support --}}
    {{-- ✅ icon support --}}
    <ul class="flex gap-05 txt-sm">
        @foreach ($menuItems as $item)
            @php
                $children = $item->children ?? null;
                $url = $item->url;
                $active = $isActive($url);
                $icon = $item->icon;
            @endphp
            @unless ($children)
                <li>
                    <a href="{{ url($url) }}" @class(["flex-vac gap-075 $itemClass", 'active' => $active])>
                        @if ($withIcons && $icon !== '')
                            <x-gt-icon name="{{ $icon }}" />
                        @endif
                        {{ $item->name }}
                    </a>
                </li>
            @endunless
        @endforeach

        {{-- parent item --}}
        {{-- <li class="relative" x-data="{ open: false }" x-on:mouseenter="open = true" x-on:mouseleave="open = false">
            <button x-on:click="open = ! open" class="flex items-center gap-075 px-075 py-05 rounded-lg txt-gray-700 hover:bg-gray-100">
                <span class="flex items-center gap-075">
                    <svg class="wh-1.25 bdr bdr-blue bg-stripes-blue"></svg>
                    Parent (Hover)
                </span>
                <svg class="wh-1" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path x-show="open" d="m6 15 6-6 6 6" />
                    <path x-show="!open" d="m6 9 6 6 6-6" />
                </svg>
            </button>
            <div x-show="open" x-collapse x-cloak class="absolute mt-05 flex w-10 z-100">
                <ul class="bx pxy-0 w-full flex-col gap-0">
                    <li>
                        <a href="#" class="flex items-center gap-075 px-075 py-05 txt-gray-700 hover:bg-gray-100">
                            Child Item
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-075 px-075 py-05 txt-gray-700 hover:bg-gray-100">
                            Child Item
                        </a>
                    </li>
                </ul>
            </div>
        </li> --}}
    </ul>
</nav>
