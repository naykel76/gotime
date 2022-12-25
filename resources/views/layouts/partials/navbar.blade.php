{{-- --------------- BEFORE YOU DO ANYTHING CRAZY -------------
DO NOT remove the container from this layout. If you don't want
the container then publish the layout locally and override. --}}

<div class="navbar">

    <div class="container">

        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ config('naykel.logo.path') }}" alt="{{ config('app.name') }}"
                    height="{{ config('naykel.logo.height') }}" width="{{ config('naykel.logo.width') }}"></a>
        </div>

        <div class="flex va-c hide-up-to-tablet">

            <x-gt-menu menuname="main" class="gg-3" itemClass="txt-white hover:txt-secondary" />

            @if(Route::has('checkout'))
                <livewire:cart-button />
            @endif

        </div>

        <div class="hide-from-tablet mxy-0">
            <x-gotime::button.burger-menu class="secondary" />
        </div>
    </div>

</div>

{{-- the sidebar needs to stay outside the navbar or things gets wild! --}}

<x:gotime::sidebar.main>

    {{-- can add addition content here --}}

</x:gotime::sidebar.main>
