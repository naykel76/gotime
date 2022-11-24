{{-- use $req instead of $required so it won't be read as the html required attribute --}}
@aware([
    'for' => null,
    'value' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'ctrlClasses' => null,
    'inline' => false,
    'req' => false,
    'shrink' => false, // allow the control to shrink
    ])

    <div class='frm-row {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

        @isset($label)
            <label for="{{ $for }}">{{ $label }} @if ($req) <span class='txt-red'>*</span> @endif </label>
        @endisset

        @if($inline)

            <div class='flex-col {{ $ctrlClasses }} {{ $shrink ? '': 'fg1' }}'>

                {{ $slot }}

                @isset($helpText)
                    <div class="help mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
                @endisset

                @error($for)
                    <small class="txt-red" role="alert"> {{ $message }} </small>
                @enderror

            </div>

            {{-- create this as slot so can add classes to conrtol only, Not form row --}}

        @else

            @isset($helpText)
                <div class="help mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endisset

            {{ $slot }}

            @error($for)
                <small class="txt-red" role="alert"> {{ $message }} </small>
            @enderror

        @endif

    </div>

    {{--
<div class="flex-col fg1{{ $icon ? ' with-icon' : null }}">

    {{ $slot }}

    @if($icon)
        <x-gotime-icon icon="{{ $icon }}" />
    @endif


    --}}
