<x-gt-layouts.base :title="str_replace('-', ' ', basename($data['path'] ?? 'Docs'))" class="nk-docs">

    <x-gt-nav filename="nav-main" menuname="main" layout="navbar" withIcons/>

    <main class="docs-layout py-3">
        <aside class="left-sidebar px space-y">
            @foreach ($data['menus'] as $menu)
                <x-gt-nav menuname="{{ $menu }}" filename="{{ $data['filename'] }}" class="menu" menu-title="{{ $menu }}" />
            @endforeach
        </aside>

        <div class="main-content-area">
            <x-gt-markdown path="{{ resource_path('views/' . $data['path']) }}" />
        </div>
    </main>

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gt-layouts.base> 

