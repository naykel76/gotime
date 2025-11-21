<x-gt-layouts.base :title="$title ?? null" :class="$bodyClass ?? null">

    {{-- @if (class_exists(\Naykel\Devit\DevitServiceProvider::class))
    @endif --}}


    <div class="dark py-05">

        <div class="container flex space-between va-c">

            <div class="flex space-x va-c">


                <a class="ml"href="{{ route('login-super') }}">Super User</a>
                <a href="{{ route('login-admin') }}">Admin User</a>
                <a href="{{ route('login-user') }}">User User</a>

                @if (Route::has('pages.all'))
                    <a href="{{ route('pages.all') }}">Site Pages</a>
                @endif

                @if (Route::has('dev'))
                    <a href="{{ route('dev') }}">Dev</a>
                @endif

                @if (Route::has('test-email'))
                    <a href="{{ route('test-email') }}">Test Email</a>
                @endif

                <a class="btn dark" href="{{ route('admin.dashboard') }}">Admin</a>
            </div>

            @auth
                <x-authit-account-dropdown />
            @else
                @if (config('authit.allow_register'))
                    <div>
                        <a class="hover:txt-secondary mr" href="{{ route('login') }}">Sign In</a>
                        <a class="hover:txt-secondary " href="{{ route('register') }}">{{ __('Register') }}</a>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <div class="to-md:hidden">
        <x-gt-nav filename="nav-main" menuname="main" layout="navbar" withIcons class="pink" />
    </div>

    <main {{ $attributes->except('title')->merge(['class' => 'nk-main']) }}>
        {{ $slot }}
    </main>

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gt-layouts.base>
