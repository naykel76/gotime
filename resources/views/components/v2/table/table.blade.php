<table {{ $attributes->class(['bdr']) }}>
    @isset($thead)
        <thead {{ $thead->attributes->class(['bg-gray-100 bdr-b']) }}>
            {{ $thead }}
        </thead>
    @endisset
    <tbody wire:loading.class="opacity-05" {{ $tbody->attributes->class(['divide-y']) }}>
        {{ $tbody }}
    </tbody>
</table>
