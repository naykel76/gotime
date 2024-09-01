{{-- seperate padding class to make it easier to override --}}
@props(['padding' => 'py-5-3-2-2'])
<div {{ $attributes->class(["flex-col lg:flex-row gap $padding"]) }}>
    <div {{ $main->attributes->merge(['class' => 'fg1']) }}>
        {{ $main }}
    </div>
    <div {{ $aside->attributes->merge(['class' => 'fs0 lg:w-20']) }}>
        {{ $aside }}
    </div>
</div>
