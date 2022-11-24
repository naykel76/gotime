{{-- NK::TD refactor to remove duplicate code --}}

<div class="light py-05">

    @if(App::environment('local'))
        <div class="container flex space-between">

            @if(Route::has('login'))

                <x-gotime-menu menuname="dev" filename="nav-admin" class="space-x" itemClass="txt-red hover:primary" />

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
