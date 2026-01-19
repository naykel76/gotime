<x-layouts.base :title="str_replace('-', ' ', basename($data['path'] ?? 'Docs'))">

    @includeFirst(['components.layouts.partials.navbar', 'gotime::components.layouts.partials.navbar'])

    <div class="nk-docs">
        <aside class="left-sidebar space-y-2">
            @foreach ($data['menus'] as $menu)
                <x-gt-nav menuname="{{ $menu }}" filename="{{ $data['filename'] }}" menu-title="{{ $menu }}" layout="dropdown" />
            @endforeach
        </aside>

        {{-- Markdown component handles all content markup. Do not wrap in
        additional containers - the component's layout controls structure for
        both main content and TOC. --}}
        <x-gt-markdown path="{{ $data['path'] }}" withToc />
    </div>

</x-layouts.base>
