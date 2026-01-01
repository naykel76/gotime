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
                    <li x-data="{ open: @js($open) }" class="relative order-{{ $order }}" x-on:click.outside="open = false" x-on:keydown.escape="open = false">
                        <x-gotime::nav.partials.parent-button :label="$item->name" :$active :$icon :$iconType />
                        <div x-show="open" x-collapse class="dropdown">
                            <ul class="bx pxy-0 w-full flex-col gap-0">
                                @include('gotime::components.nav.partials.children', ['children' => $item->children])
                            </ul>
                        </div>
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
    {{ $slot }}
</x-gotime::nav.base>
