<div class="navbar">
    <div class="container va-c">
        <a href="{{ url('/') }}" class="flex-centered">
            <img src="{{ config('gotime.logo.path') }}" alt="{{ config('app.name') }}">
        </a>
        <div class="to-md:hidden mxy-0">
            <x-gt-menu layout="dropdown" itemClass="rounded-lg txt-white hover:txt-white bg-stone-800 hover:bg-gray-800 px-1 py-075" />
        </div>
    </div>
    {{-- <div class="md:hidden mxy-0">
        <x-gt-sidebar layout="burger-button-main" />
    </div> --}}
</div>
