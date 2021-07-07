<nav {{ $attributes }}>
    {{-- $menuname is a parameter passed in with component --}}
    {{-- use named route if available, check if named route exists and not null --}}
    @foreach($menu->$menuname->links as $item)

        @if(isset($item->route_name) && !empty($item->route_name) && Route::has($item->route_name))
            <a href="{{ route($item->route_name) }}">{{ $item->title }}</a>
        @else
            <a href="{{ url($item->url) }}">{{ $item->title }}</a>
        @endif

    @endforeach

    {{ $slot }}
</nav>