@aware([
    'for' => null,
    'value' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'inline' => false,
    'icon' => null,
    'req' => false,
    'ignoreErrors' => false
])

    <div class='frm-row
        {{ $icon ? 'with-icon' : '' }}
        {{ $inline ? 'inline' : '' }}
        {{ $rowClass }}'>

        @isset($label)
            <label for="{{ $for }}">{{ Str::title($label) }} @if ($req) <span class='txt-red'>*</span> @endif </label>
        @endisset

        @isset($helpText)
            <div class="help mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
        @endisset

        <div class="flex-col fg1{{ $icon ? ' with-icon' : null }}">

            {{ $slot }}

            @if($icon)
                <x-gotime-icon icon="{{ $icon }}" />
            @endif

            @if (!$ignoreErrors)
                @error($for)
                <small class="txt-red" role="alert"> {{ $message }} </small>
                @enderror
            @endif

        </div>

    </div>
