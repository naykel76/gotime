@foreach ($children as $child)
    <li>
        <a href="{{ $child->url }}" >
        {{-- <a href="{{ $child->url }}" @class(['menu-link', 'active' => $isActive($child->url)])> --}}
            <x-gotime::icon-label :label="$child->name" />
        </a>
    </li>
@endforeach
