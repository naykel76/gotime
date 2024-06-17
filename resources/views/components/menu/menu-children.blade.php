@props(['children'])

@foreach ($children as $child)
    <a href="{{ url($child->url) }}"> {{ $child->name }} </a>
@endforeach
