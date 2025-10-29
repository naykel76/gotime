@props(['hasAside' => true])

<x-gt-app-layout layout="base" :$title>


    @includeFirst(['components.layouts.partials.navbar', 'gotime::components.layouts.partials.navbar'])


    <main {{ $attributes->class(['nk-main container py-2 md:py-5', $hasAside ? 'flex gap-5' : '']) }}>

        @isset($navigation)
            <aside class="w-18 fs0" {{ $navigation->attributes }}>
                {{ $navigation }}
            </aside>
        @endisset

        {{-- style="min-width: 0; is to prevent the code blocks overflowing because flexbox is being a dick! --}}
        <div class="fg1" style="min-width: 0;">
            {{ $slot }}
        </div>

    </main>

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gt-app-layout>
