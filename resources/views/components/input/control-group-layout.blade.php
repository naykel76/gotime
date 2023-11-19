@aware([ 'for' => null,
    'value' => null,
    'label' => null,
    'tooltip' => false,
    'ignoreErrors' => false,
    'helpText' => null,
    'helpTextTop' => false,
    'rowClass' => null,
    'labelClass' => null,
    'inline' => false ])

    <div class='frm-row  {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

        @isset($label)
            @if($tooltip)
                <div class="flex va-c space-between">
                    <x-gt-label></x-gt-label>
                    <x-gt-tooltip class="danger"> {{ $tooltip }} </x-gt-tooltip>
                </div>
            @else
                <x-gt-label></x-gt-label>
            @endif
        @endisset

        <div class="flex-col w-full">

            @if(isset($helpText) && $helpTextTop)
                <div class="mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endif

            {{ $slot }}

            @if(isset($helpText) && ! $helpTextTop)
                <div class="mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endif

            @unless($ignoreErrors)
                @error($for)
                    <small class="txt-red" role="alert"> {{ $message }} </small>
                @enderror
            @endunless

        </div>

    </div>
