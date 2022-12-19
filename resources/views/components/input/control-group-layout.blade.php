@aware([ 'for' => null,
    'value' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'inline' => false,
    'req' => false,
    'ignoreErrors' => false ,])


    <div class='frm-row  {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

        @isset($label)
            <label for="{{ $for }}">{{ Str::title($label) }}
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
