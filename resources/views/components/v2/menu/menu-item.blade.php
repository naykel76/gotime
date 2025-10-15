@props(['order' => null])

<li {{ $attributes }} @class(["order-$order" => $order])>
    {{ $slot }}
</li>
