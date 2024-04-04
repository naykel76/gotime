<x-gotime::menu.base {{ $attributes }}>

    @foreach ($menu->links as $item)
        @php
            $url = $getUrl($item);
            $active = $isActive($url);
        @endphp

        @if (!$isParent($item))
            <li>
                <x-gotime::menu.menu-link :$url :$active :$itemClass :$newWindow> {{ $item->name }} </x-gotime::menu.menu-link>
            </li>
        @else
            <li class="relative" x-data="{ showChildren: false }" x-on:mouseenter="showChildren=true" x-on:mouseleave="showChildren=false">

                <x-gotime::menu.menu-link :$url :$active :$itemClass :$newWindow :isParent=$isParent($item)> {{ $item->name }} </x-gotime::menu.menu-link>

                <!-- wrapper for child menu -->
                <div class="absolute mt-05 flex w-16 z-100" x-show="showChildren" x-transition.duration style="display: none;">
                    <!-- child menu -->
                    <div class="menu bx pxy-0 w-full">
                        @foreach ($item->children as $child)
                            @php
                                $childUrl = $getUrl($child);
                            @endphp
                            <!-- child menu -->
                            <a href="{{ url($childUrl) }}">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
        @endif
    @endforeach

    {{ $slot }}

</x-gotime::menu.base>
