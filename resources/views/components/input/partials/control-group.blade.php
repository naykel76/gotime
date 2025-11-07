@aware([
    'for' => null,
    'value' => null,
    'label' => null,
    'tooltip' => false,
    'ignoreErrors' => false,
    'helpText' => null,
    'helpTextTop' => false,
    'rowClass' => null,
    'labelClass' => null,
    'inline' => false,
    'componentName' => '', // included to allow special conditions like the label for the checkbox component
])

<x-gotime::.input.partials.form-row
    {{ $attributes->merge([
        'class' => ($inline ? ' inline' : '') . ' ' . $rowClass,
    ]) }}>

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

        @unless ($ignoreErrors)
            <x-gotime::.input.partials.error :for="$for" />
        @endunless

    </div>

</x-gotime::.input.partials.form-row>
