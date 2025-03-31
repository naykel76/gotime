{{-- scripts and styles for ckeditor --}}

{{ $slot }}

@pushOnce('scripts')
    <script src="{{ asset('js/nk-ckeditor.js') }}"></script>
@endPushOnce