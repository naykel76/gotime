@aware([
    'for' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'inline' => false,
    'req' => false,
    ])



    <div class='frm-row
        {{ $inline ? 'inline' : '' }}
        {{ $rowClass }}'>

        @isset($label)
            <label>{{ $label }} @if ($req) <span class='txt-red'>*</span> @endif </label>
        @endisset

        <div>

            {{ $slot }}

            @error($for)
                <div><small class="txt-red" role="alert"> {{ $message }} </small></div>
            @enderror
        </div>

    </div>
