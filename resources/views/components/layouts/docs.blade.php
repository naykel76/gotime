<x-gt-layouts.base :title="str_replace('-', ' ', basename($data['path'] ?? 'Docs'))" class="nk-docs">

    <div class="to-md:hidden">
        <x-gt-nav filename="nav-main" menuname="main" layout="navbar" withIcons class="pink" />
    </div>

    <div class="docs-layout">
        
        <aside class="left-sidebar px space-y">
            @foreach ($data['menus'] as $menu)
                <x-gt-nav menuname="{{ $menu }}" filename="{{ $data['filename'] }}" class="menu" menu-title="{{ $menu }}" />
            @endforeach
        </aside>

        <x-gt-markdown path="{{ resource_path('views/' . $data['path']) }}" />

    </div>


    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gt-layouts.base>
