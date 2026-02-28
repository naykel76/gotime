<x-gotime::nav.base :class="$navClass ?? null">
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
                    @include('gotime::components.nav.partials.item-link')
                @endif
            @endcanany
        @endforeach
    </ul>
    {{ $slot }}
</x-gotime::nav.base>
