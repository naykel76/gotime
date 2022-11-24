@if(config('naykel.account.register'))
    @auth
        {{-- <x-authit::account-dropdown /> --}}
    @else
        <x-authit::login-register-buttons />
    @endauth
@endif
