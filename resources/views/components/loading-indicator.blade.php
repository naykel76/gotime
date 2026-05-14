@props(['size' => '5', 'color' => 'gray-400'])

<div {{ $attributes->class(['overlay flex-centered va-c mxy-0']) }}>
    <div class="spinner-dots {{ 'wh-' . $size }} {{ 'txt-' . $color }}">
        <div></div><div></div><div></div><div></div>
        <div></div><div></div><div></div><div></div>
    </div>
    {{ $slot }}
</div>
