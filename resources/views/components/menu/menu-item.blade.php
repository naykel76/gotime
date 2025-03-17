@props([
    'order' => null,
    'active' => false,
    // props for the `menu-link` component
    'url' => null,
    'text' => null,
])

<li {{ $attributes }} @class(["order-$order" => $order])>
    
    {{ $slot }}

    @if ($url)
        <x-gt-menu-link :$url :$text  />
    @endif
</li>
