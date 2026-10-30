<x-gotime::nav.base :class="$navClass">
    <ul {{ $attributes->merge(['class' => 'menu']) }}>
        @foreach ($menuItems as $item)
            <li>
                <a href="{{ $item->url }}">{{ $item->name }}</a>
            </li>
        @endforeach
    </ul>
</x-gotime::nav.base>
