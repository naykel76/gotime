{{-- NK::TD refactor to remove duplicate code --}}

<div class="light py-05">

    @if(App::environment('local'))
        <div class="container flex space-between">

            @if(Route::has('login'))
                {{-- these routes will start working when all the moons align
                and there is nothing left to complain about! --}}
                <nav class="space-x">
                    @if(Route::has('user1'))
                        <a href="{{ route('user1') }}">Quick Login</a>
                    @endif
                    @auth
                        <a href="{{ route('admin') }}">Admin</a>
                    @endauth
                    <a href="/dev">Dev</a>
                </nav>

                <div>
                    @auth
                        <x-authit-account-dropdown />
                    @else
                        <a class="hover:txt-secondary mr" href="{{ route('login') }}">Sign In</a>
                        <a class="hover:txt-secondary " href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endauth
                </div>

            @endif

        </div>

    @else

        <div class="container flex ha-r">

            @if(Route::has('login'))

                <div>
                    @auth
                        <x-authit-account-dropdown />
                    @else
                        <a class="hover:txt-secondary mr" href="{{ route('login') }}">Sign In</a>
                        <a class="hover:txt-secondary " href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endauth
                </div>

            @endif

        </div>

    @endif

</div>
