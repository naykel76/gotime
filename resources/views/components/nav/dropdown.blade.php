@props(['menuTitle' => '', 'navClass' => ''])

<nav class="{{ $navClass }}">
    @if ($menuTitle)
        <div class="menu-title">{{ Str::upper($menuTitle) }}</div>
    @endif

    <ul {{ $attributes->merge(['class' => 'menu']) }}>
        @foreach ($menuItems as $item)
            @php
                $hasChildren = !empty($item->children);
                $url = $item->url;
                $active = $isActive($url);
                $icon = $item->icon ?? '';
                $showIcon = $withIcons && $icon !== '';
            @endphp

            @if ($hasChildren)
                <li x-data="{ open: @js($open) }" x-on:click.away="open = false" class="relative">
                    <button x-on:click="open = !open" @class(['active' => $active])>
                        <x-gotime::.icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                        <x-gotime::.chevron-toggle />
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
                <li>
                    <a href="{{ url($url) }}" @class(['active' => $active])>
                        <x-gotime::.icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
