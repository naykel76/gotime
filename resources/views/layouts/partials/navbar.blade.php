{{-- --------------- BEFORE YOU DO ANYTHING CRAZY -------------
DO NOT remove the container from this layout. If you don't want
the container then publish the layout locally and override. --}}

<div class="navbar">

    <div class="container">

        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ config('naykel.logo.path') }}" height="{{ config('naykel.logo.height') }}" alt="{{ config('app.name') }}"></a>
        </div>

        <div class="flex va-c hide-up-to-tablet">

            {{-- main navigation --}}
            <x-gotime-menu menuname="main" class="gg-3" itemClass="txt-white hover:txt-secondary"/>

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
