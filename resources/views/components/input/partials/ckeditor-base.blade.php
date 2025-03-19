{{-- scripts and styles for ckeditor --}}

{{ $slot }}

@assets('scripts')
    <script src="{{ asset('js/ckeditor.js') }}"></script>

    <style>
        .ck.ck-balloon-panel.ck-balloon-panel_position_border-side_right.ck-powered-by-balloon {
            display: none;
        }

        .ck.ck-editor__editable_inline {
            background: var(--ck-color-base-background);
            border: 1px solid var(--ck-color-base-border);
        }
    </style>
@endassets

