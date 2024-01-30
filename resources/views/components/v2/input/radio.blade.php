@props([ 'for' => null, 'controlOnly' => false, 'options' => [],
    'value' => null, 'label' => null, 'rowClass' => null,
    'tooltip' => false,
    'helpText' => null,
    'helpTextTop' => false,
    ])

    {{-- @aware([ 'inline' => false ]) --}}

    @if($controlOnly)
        <x-gotime::v2.input.control-radio {{ $attributes }} />
    @else
        <div class='frm-row {{ $rowClass }}'>

            @if(isset($helpText) && $helpTextTop)
                <div class="mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endif

            @isset($label)
                <x-gt-label :tooltip="$tooltip ?? null" />
            @endisset

            <div class="flex space-x w-full">
                @foreach($options as $key => $option)
                    <x-gotime::v2.input.control-radio {{ $attributes->merge(['value' => $key]) }} :$option />
                @endforeach
            </div>

            @if(isset($helpText) && ! $helpTextTop)
                <div class="mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endif

            @error($for)
                <small class="txt-red mt-025" role="alert"> {{ $message }} </small>
            @enderror

        </div>

    @endif
