<main {{ $attributes->merge(['class' => !empty($mainClasses) ? $mainClasses : null]) }}>

    @if($hasContainer) <div class="container"> @endif

        @if($hasTitle) <h1>{{ $pageTitle }}</h1> @endif

        {{ $slot }}

    @if($hasContainer) </div> @endif

</main>
