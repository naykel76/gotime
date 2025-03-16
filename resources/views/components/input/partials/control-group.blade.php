@aware(['for' => null, 'value' => null, 'label' => null, 'tooltip' => false, 'ignoreErrors' => false, 'helpText' => null, 'helpTextTop' => false, 'rowClass' => null, 'labelClass' => null, 'inline' => false])

<x-gotime::input.partials.form-row
    {{ $attributes->merge([
        'class' => ($inline ? ' inline' : '') . ' ' . $rowClass,
    ]) }}>

    @isset($label)
        <x-gotime::input.partials.label :tooltip="$tooltip ?? null" />
    @endisset

    <div class="flex-col w-full my-0">

        @if (isset($helpText) && $helpTextTop)
            <x-gotime::input.partials.help-text :$helpText />
        @endif

        {{ $slot }}

        @if (isset($helpText) && !$helpTextTop)
            <x-gotime::input.partials.help-text :$helpText />
        @endif

        @unless ($ignoreErrors)
            @error($for)
                <p class="mt-025 txt-xs txt-red-600" role="alert">
                    {{ $message }}
                </p>
            @enderror
        @endunless

    </div>

</x-gotime::input.partials.form-row>
