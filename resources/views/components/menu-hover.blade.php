<x-gotime::menu-layout {{ $attributes }}>

    @foreach($menu->links as $item)

        @php
            $url = $getUrl($item);
            $active = $isActive($url);
        @endphp

        @if(!$isParent($item))
            <li>
                <x-gt-menu-link :$url :$active :$itemClass> {{ $item->name }} </x-gt-menu-link>
            </li>
        @else

            <li class="relative" x-data="{showChildren:false}" x-on:mouseenter="showChildren=true" x-on:mouseleave="showChildren=false">

                <x-gt-menu-link :$url :$active :$itemClass :isParent=$isParent($item)> {{ $item->name }} </x-gt-menu-link>

                <!-- wrapper for child menu -->
                <div class="absolute mt-05 flex w-16 z-100" x-show="showChildren" x-transition.duration style="display: none;">

                    <!-- child menu -->
                    <div class="menu bx pxy-0 w-full">

                        @foreach($item->children as $child)

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

</x-gotime::menu-layout>
