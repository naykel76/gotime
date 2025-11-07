@aware([
    'for' => null,
    'value' => null,
    'label' => null,
    'tooltip' => false,
    'helpText' => null,
    'helpTextTop' => false,
    'rowClass' => null,
    'componentName' => '', // included to allow special conditions like the label for the checkbox component
])

<x-gotime::.input.partials.form-row
    {{ $attributes->merge(['class' => $rowClass]) }}>

    {{-- Render label unless component is a checkbox, as checkboxes handle labels differently --}}
    @if (isset($label) && $componentName !== 'checkbox')
        <x-gotime::.input.partials.label :tooltip="$tooltip ?? null" />
    @endif

    <div class="flex-col w-full my-0">

        @if (isset($helpText) && $helpTextTop)
            <x-gotime::.input.partials.help-text :$helpText />
        @endif

        {{ $slot }}

        @if (isset($helpText) && !$helpTextTop)
            <x-gotime::.input.partials.help-text :$helpText />
        @endif

        <x-gotime::.input.partials.error :for="$for" />

    </div>

</x-gotime::.input.partials.form-row>
