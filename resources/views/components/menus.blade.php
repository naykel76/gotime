<h1>Add menus! This component is WIP</h1>

To work around this add partials/admin.nav.blade.php and include the menus

{{-- <nav {{ $attributes }}>

    @foreach($file as $menuname => $linksArr)

        @if ($withHeaders)
        <div class="menu-title"> {{ Str::title($menuname) }}</div>
        @endif

       load menu

    @endforeach

    {{ $slot }}

</nav> --}}
