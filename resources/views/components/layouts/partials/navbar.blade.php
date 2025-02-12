{{-- --------------- BEFORE YOU DO ANYTHING CRAZY -------------
DO NOT remove the container from this layout. If you don't want
the container then publish the layout locally and override. --}}

<div class="navbar">
    <div class="container">
        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ config('gotime.logo.path') }}" alt="{{ config('app.name') }}"
                    height="{{ config('gotime.logo.height') }}" width="{{ config('gotime.logo.width') }}"></a>
        </div>
        <div class="flex va-c to-md:hidden">
            <x-gt-menu layout="hover" class="gap-1" itemClass="nav-item rounded-05" />
        </div>
    </div>
    <div class="md:hidden mxy-0">
        <x-gt-sidebar layout="burger-button-main" />
    </div>
</div>
