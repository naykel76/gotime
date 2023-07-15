@props(['withIcons' => false, 'iconClass' => '', 'itemClass' => ''])

    <nav {{ $attributes }}>

        <ul>

            @if ($title)
                <div class="menu-title">{{ $title }}</div>
            @endif

            @foreach($menu->links as $item)

                @php
                    $url = $getUrl($item);
                    $active = $isActive($url);
                @endphp

                @if(!$isParent($item))
                    <x-gt-menu-link :$url :$active :$itemClass> {{ $item->name }} </x-gt-menu-link>
                @else

                    <li class="relative" x-data="{showChildren:false}" x-on:mouseenter="showChildren=true" x-on:mouseleave="showChildren=false">

                        {{-- this creates a hash when there is no clickable link and it really should be updated to a button! --}}
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
        </ul>

    </nav>
