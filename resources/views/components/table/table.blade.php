<table {{ $attributes}}>
    @isset($thead)
        <thead {{ $thead->attributes->class(['bg-gray-100']) }}>
            {{ $thead }}
        </thead>
    @endisset
    <tbody wire:loading.class="opacity-50" {{ $tbody->attributes }}>
        {{ $tbody }}
    </tbody>
</table>
