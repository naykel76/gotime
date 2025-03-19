{{-- this layout is deprecated in place of using control-layout  --}}
@aware(['for', 'value' => null, 'label' => null, 'tooltip' => false, 'ignoreErrors' => false, 'helpText' => null, 'helpTextTop' => false, 'rowClass' => null, 'labelClass' => null])

<div class='frm-row {{ $rowClass }}'>
    @isset($label)
        <x-gotime::input.partials.label :tooltip="$tooltip ?? null" />
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

@assets('scripts')
    <script src="{{ asset('js/ckeditor.js') }}"></script>

    <style>
        .ck.ck-editor__editable_inline>:last-child {
            margin-bottom: 0;
        }

        .ck.ck-editor__editable_inline>:first-child {
            margin-top: 0;
        }

        .ck-editor__main .ck.ck-editor__editable_inline {
            padding: .6rem !important;
        }

        .ck.ck-balloon-panel.ck-balloon-panel_position_border-side_right.ck-powered-by-balloon {
            display: none;
        }

        .ck.ck-editor__editable_inline {
            padding: 0 !important;
        }

        .ck.ck-editor__editable.ck-focused {
            padding-block: 0.25rem !important;
            padding: 0.6rem !important;
        }
    </style>
@endassets
