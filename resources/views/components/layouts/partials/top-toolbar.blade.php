<div class="py-05">
    <div class="container flex ha-r">
        @if (Route::has('login'))
            @auth
                <x-authit-account-dropdown />
            @else
                <a class="hover:txt-secondary mr" href="{{ route('login') }}">Sign In</a>
                <a class="hover:txt-secondary " href="{{ route('register') }}">{{ __('Register') }}</a>
            @endauth
        @endif
    </div>
</div>
