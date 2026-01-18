<ul {{ $attributes->merge(['class' => 'menu']) }}>
    @foreach ($menuItems as $item)
        @php
            $active = $isActive($item->url);
            $order = $item->order ?? $loop->index;
            $icon = $withIcons && $item->icon ? $item->icon : null;
            $iconType = $withIcons && $item->iconType ? $item->iconType : null;
        @endphp
        @canany($item->permissions)
            @if ($item->hasChildren)
                <li x-data="{ open: @js($open) }" class="order-{{ $order }}">
                    <x-gotime::nav.partials.parent-button :label="$item->name" :$active :$icon :$iconType />
                    <ul x-show="open" x-collapse>
                        @include('gotime::components.nav.partials.children', ['children' => $item->children])
                    </ul>
                </li>
            @else
                <li class="order-{{ $order }}">
                    <a href="{{ $item->url }}" {{ $active ? 'class="active"' : '' }}>
                        <x-gotime::icon-label :label="$item->name" :$icon :$iconType />
                    </a>
                </li>
            @endif
        @endcanany
    @endforeach
</ul>
