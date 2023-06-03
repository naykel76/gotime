<x-gotime-app-layout layout="{{ config('naykel.template') }}" hasContainer class="py-5-3-2">

    <div class="flex gg-5-3-2 pxy">

        <div class="bx w-20 to-md:hide">

            <div class="tac mb-2">
                <img class="wh-200px round" src="{{ auth()->user()->avatarUrl() }}" alt="Profile Photo">
            </div>

            <x-authit::user-navigation />

        </div>

        <div class="fg1">

            {{ $slot }}

        </div>

    </div>

</x-gotime-app-layout>
