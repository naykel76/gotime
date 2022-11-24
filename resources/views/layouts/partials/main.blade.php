<main id="nk-main" {{ $attributes }}>

    @if($hasContainer) <div class="container"> @endif

        {{ $slot }}

    @if($hasContainer) </div> @endif

</main>
