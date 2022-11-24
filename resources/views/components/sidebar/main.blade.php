<x:gotime::sidebar>

    @if(Route::has('login'))

        @auth
            <x-gotime-menu menuname="main" filename="nav-user" class="menu" />
        @else
            <div class="px">
                {{-- use @include to prevent error if package not used --}}
                @include('authit::components.login-register-buttons')
            </div>
        @endauth

        <hr class="my-1">

    @endif

    <x-gotime-menu menuname="main" class="menu" />

    {{ $slot }}

</x:gotime::sidebar>
