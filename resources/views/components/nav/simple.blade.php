<nav {{ $navClass ? 'class=' . $navClass : '' }}>
    <ul {{ $attributes->merge(['class' => 'menu']) }}>
        @foreach ($menuItems as $item)
            @php
                $active = $isActive($item->url);
                $order = $item->order ?? $loop->index;
                $icon = $item->icon ?? '';
                $showIcon = $withIcons && $icon !== '';
            @endphp
            <li class="order-{{ $order }}">
                <a href="{{ url($item->url) }}" {{ $active ? 'class=active' : '' }}>
                    <x-gotime::icon-label :label="$item->name" :icon="$showIcon ? $icon : null" />
                </a>
            </li>
        @endforeach
    </ul>
    {{ $slot }}
</nav>