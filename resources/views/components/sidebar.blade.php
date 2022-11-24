<div x-data="{ open: false }" x-show="open" x-on:keydown.escape.window="open = false" x-cloak @open-sidebar.window="open = true" class="overlay" @click="open = false">

    <div class="sidebar light">

        <div class="flex space-between pxy va-c bdr-b">

            <img src="{{ config('naykel.logo.sidebar_path') }}" height="{{ config('naykel.logo.sidebar_height') }}" alt="{{ config('app.name') }}">

            <a @click="open = false">
                <x-gotime-icon icon="close" class="close" />
            </a>

        </div>

        {{ $slot }}

    </div>

</div>
