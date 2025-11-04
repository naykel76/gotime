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
                <li x-data="{ open: @js($open) }">
                    <button x-on:click="open = !open" @class(['active' => $active])>
                        <x-gotime::v2.icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                        <x-gotime::v2.chevron-toggle />
                    </button>
                    <ul x-show="open" x-collapse>
                        @foreach ($item->children as $child)
                            <li>
                                <a href="{{ url($child->url) }}" @class(['menu-link', 'active' => $isActive($child->url)])>
                                    <x-gotime::v2.icon-label :label="$child->name" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ url($url) }}" @class(['active' => $active])>
                        <x-gotime::v2.icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
