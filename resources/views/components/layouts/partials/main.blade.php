@props(['hasContainer' => false])
<main {{ $attributes->merge(['class' => 'nk-main']) }}>

    @if ($hasContainer)
        <div class="container">
    @endif

    {{ $slot }}

    @if ($hasContainer)
        </div>
    @endif

</main>
