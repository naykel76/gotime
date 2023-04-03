@aware([ 'for' => null,
    'value' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'labelClass' => null,
    'inline' => false,
    'req' => false,
    'ignoreErrors' => false ,])

{{-- use flex basis to change label width??? --}}
    <div class='frm-row  {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

        @isset($label)
            <label for="{{ $for }}" @if ($labelClass) class="{{ $labelClass }}" @endif >{{ Str::title($label) }}
                @if ($req) <span class='txt-red'>*</span> @endif </label>
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
