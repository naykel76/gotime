<nav @if ($navClass) class="{{ $navClass }}" @endif>
    <ul {{ $attributes->merge(['class' => 'menu']) }}>
        @foreach ($menuItems as $item)
            @php
                $active = $isActive($item->url);
                $order = $item->order ?? $loop->index;
                $icon = $withIcons && $item->icon ? $item->icon : null;
            @endphp
            @canany($item->permissions)
                <li class="order-{{ $order }}">
                    <a href="{{ $item->url }}" {{ $active ? 'class="active"' : '' }}>
                        <x-gotime::icon-label :label="$item->name" :$icon />
                    </a>
                </li>
            @endcanany
        @endforeach
    </ul>
    {{ $slot }}
</nav>
