<x-gt-layouts.base :title="$title ?? 'Markdown Page'">

    <div class="to-md:hidden">
        <x-gt-nav filename="nav-main" menuname="main" layout="navbar" withIcons class="pink" />
    </div>
     
    <div class="container-md py-2">
        <x-gt-markdown path="{{ resource_path('views/' . $data['path']) }}" />
    </div>
    
    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])
    
</x-gt-layouts.base>
