<nav @class(['navbar', $navClass])>
    <div class="flex-centered gap-05">
        <a href="{{ url('/') }}" class="flex">
            <img src="{{ asset('favicon.svg') }}" alt="{{ config('app.name') }}"class="bg-yellow-50 pxy-025 rounded"
                height="{{ config('gotime.logo.height') }}" width="{{ config('gotime.logo.width') }}">
        </a>
        <div class="txt-2.5 fw7">NAYKEL</div>
    </div>
    <ul {{ $attributes->merge(['class' => 'menu']) }}>
        @foreach ($menuItems as $item)
            @php
                $hasChildren = !empty($item->children);
                $url = $item->url;
                $active = $isActive($url);
                $icon = $item->icon ?? '';
                $showIcon = $withIcons && $icon !== '';
                $order = $item->order ?? $loop->index;
            @endphp
            @canany([$item->permission, !$item->permission])
                @if ($hasChildren)
                    <li x-data="{ open: @js($open) }" class="relative order-{{ $order }}" x-cloak x-on:click.outside="open = false" x-on:keydown.escape="open = false">
                        <button x-on:click="open = !open" @class(['active' => $active])>
                            <x-gotime::.icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                            {{-- <x-gotime::.chevron-toggle /> --}}
                            {{-- <svg x-cloak class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path x-show="open" d="m6 15 6-6 6 6" />
                                <path x-show="!open" d="m6 9 6 6 6-6" />
                            </svg> --}}
                        </button>
                        <div x-show="open" x-collapse class="absolute mt-05 flex w-10 z-100">
                            <ul class="bx pxy-0 w-full flex-col gap-0">
                                @foreach ($item->children as $child)
                                    <li>
                                        <a href="{{ url($child->url) }}" @class(['menu-link', 'active' => $isActive($child->url)])>
                                            <x-gotime::.icon-label :label="$child->name" />
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="order-{{ $order }}">
                        <a href="{{ url($url) }}" @class(['active' => $active])>
                            <x-gotime::.icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                        </a>
                    </li>
                @endif
            @endcanany
        @endforeach
    </ul>
    {{ $slot }}
</nav>
