<x-gt-app-layout layout="docs" class="container markdown">
    <x-slot name="navigation">
        @if (empty($data['menus']))
            <p class="bx warning mr-1 tac">No Menus to display. <br> Maybe you should get onto that!</p>
        @else
            <div class="space-y-2">
                @foreach ($data['menus'] as $menu)
                    <x-gt-menu menuname="{{ $menu }}" filename="{{ $data['filename'] }}" class="menu txt-sm"
                        title="{{ $menu }}" />
                @endforeach
            </div>

        @endif
    </x-slot>

    @if (!empty($data['type']))
        @dd('when you see this find out what it is doing!')
        @include($data['path'])
    @elseif(!empty($data['path']))
        <x-gt-markdown path="{{ resource_path('views/' . $data['path']) }}" />
    @endif
</x-gt-app-layout>
