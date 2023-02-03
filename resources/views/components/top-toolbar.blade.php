
<div class="light py-05">

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

</div>
