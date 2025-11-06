{{-- this has the markdown component in the markdown layout --}}
<x-gt-layouts.base :title="$title ?? 'Markdown Page'">

    {{-- @includeFirst(['components.layouts.partials.navbar', 'gotime::components.layouts.partials.navbar']) --}}
     
    <div class="container-md py-2">
        <x-gt-markdown path="{{ resource_path('views/' . $data['path']) }}" />
    </div>
    
    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])
    
</x-gt-layouts.base>
