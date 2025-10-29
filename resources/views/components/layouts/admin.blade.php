@push('head')
    <meta name="robots" content="noindex,follow">
@endpush

<x-gt-app-layout layout="base" :$title class="nk-admin relative">
    <div class="navbar">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <x-gt-icon name="naykel" type="other" />
            </a>
        </div>
        <div class="flex">
            <nav> <a href="/" class="btn pink mr" target="blank">Home Page</a> </nav>
            <x-authit-account-dropdown class="right-0" btn-class="txt-white" />
        </div>
    </div>
    <main class="nk-main flex">
        <aside class="bdr-r py bg-gray-50 minw-20">
            @includeFirst([ 'components.layouts.partials.admin-nav', 'gotime::components.layouts.partials.admin-nav', ])
        </aside>
        <div {{ $attributes->class(['container py-2']) }}>
            {{ $slot }}
        </div>
    </main>
</x-gt-app-layout>