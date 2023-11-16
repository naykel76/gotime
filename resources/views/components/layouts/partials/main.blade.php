<main {{ $attributes->merge(['class' => !empty($mainClasses) ? $mainClasses : null]) }}>

    @if($hasContainer) <div class="container"> @endif

        {{-- optionally add a page title in main element (default = false) --}}
        @if($hasTitle) <h1>{{ $pageTitle }}</h1> @endif

        {{ $slot }}

    @if($hasContainer) </div> @endif

</main>
