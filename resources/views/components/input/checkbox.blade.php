@props([
    'for' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'isChecked' => false,
    'ignoreErrors' => false, // allows to manage locally
    ])

{{-- This needs to be tested on laravel only --}}
{{-- {{ old($for) ? (old($for) ? "checked='checked'" : null) : ($isChecked ? "checked='checked'" : null) }} --}}

<div class='frm-row ha-c {{ $rowClass }}'>

    <label>

        <input type="checkbox" {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes }}
            @checked(old($for, $isChecked))
        />

        <span class="ml-05">{{ $label }}</span>

    </label>

    @isset($helpText)
        <div class="help"> <small>{{ $helpText }}</small> </div>
    @endisset

    @if (!$ignoreErrors)
        @error($for)
            <div class="txt-red" role="alert"> {{ $message }} </div>
        @enderror
    @endif

</div>
