@props(['id' => null, 'maxWidth' => null])

    <x-gt-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>

        @isset($title)
            <div class="bx-title">{{ $title }}</div>
        @endisset

        {{ $slot }}

        @isset($footer)
            <div class="bx-footer tar">
                {{ $footer }}
            </div>
        @endisset

    </x-gt-modal>
