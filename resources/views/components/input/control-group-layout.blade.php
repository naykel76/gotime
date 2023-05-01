@aware([ 'for' => null,
    'value' => null,
    'tooltip' => false,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'labelClass' => null,
    'inline' => false,
    'req' => false,
    'ignoreErrors' => false ,])

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

            {{ $slot }}

            @isset($helpText)
                <div class="help mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endisset

            @unless($ignoreErrors)
                @error($for)
                    <small class="txt-red" role="alert"> {{ $message }} </small>
                @enderror
            @endunless

        </div>

    </div>
