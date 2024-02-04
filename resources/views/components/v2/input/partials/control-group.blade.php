@aware(['for' => null, 'value' => null, 'label' => null, 'tooltip' => false, 'ignoreErrors' => false, 'helpText' => null, 'helpTextTop' => false, 'rowClass' => null, 'labelClass' => null, 'inline' => false])

<div class='frm-row{{ $inline ? ' inline' : '' }} {{ $rowClass }}'>

    @isset($label)
        <x-gt-label :tooltip="$tooltip ?? null" />
    @endisset

    <div class="flex-col w-full my-0">

        @if (isset($helpText) && $helpTextTop)
            <div class="mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
        @endif

        {{ $slot }}

        @if (isset($helpText) && !$helpTextTop)
            <div class="mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
        @endif

        @unless ($ignoreErrors)
            @error($for)
                <small class="txt-red" role="alert"> {{ $message }} </small>
            @enderror
        @endunless

    </div>

</div>
