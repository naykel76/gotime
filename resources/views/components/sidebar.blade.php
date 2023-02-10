{{-- This component uses sidebar::class --}}

<div x-cloak x-data="{ open: false }" @keydown.escape.window="open = false">

    {{ $toggle }}

    <div x-show="open" class="overlay"></div>

    <div class="sidebar transition w-20 light"
        :class="{'-translate-x-full opacity-0':open === false, 'translate-x-0 opacity-100': open === true}">

        @if(!isset($header))
            <div class="flex space-between px-075 py-05 va-c bdr-b">
                <div class="logo">
                    <a href="{{ url('/') }}"><img src="{{ config('naykel.logo.path') }}" alt="{{ config('app.name') }}"
                            height="{{ config('naykel.logo.height') }}" width="{{ config('naykel.logo.width') }}"></a>
                </div>
                <button x-on:click="open = false" type="button" class="btn dark pxy-025">
                    <svg class="icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @else
            {{ $header }}
        @endif

        {{ $slot }}

        @isset($footer)
            {{ $footer }}
        @endisset

    </div>

</div>

