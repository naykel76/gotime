{{-- This table component is intentionally minimal. Add utility classes as needed or use specialised
components like gt-table.th for more specific functionality. --}}

<table class="bdr">
    @isset($thead)
        <thead {{ $thead->attributes->class(['bg-gray-100 bdr-b']) }}>
            {{ $thead }}
        </thead>
    @endisset
    <tbody wire:loading.class="opacity-05" {{ $tbody->attributes->class(['divide-y']) }}>
        {{ $tbody }}
    </tbody>
</table>
