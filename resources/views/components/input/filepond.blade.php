{{-- *** COMPONENT BASED CLASS *** --}}

{{-- do not use the control layout here because it causes problems when displaying errors --}}
<div x-data="{ initFilePond }" wire:ignore x-init="initFilePond()">
    <input type="file" x-ref="input" style="display:none">
</div>

@pushOnce('styles')
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <style>
        .filepond--root {
            margin-bottom: 0 !important;
        }

        .filepond--credits {
            display: none;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
@endPushOnce

@push('scripts')
    <script>
        function initFilePond() {
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            // Set global options for all FilePond instances
            FilePond.setOptions({
                allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
                maxFileSize: '{{ $maxFileSize }}KB',
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                    },
                }
            });
            // Create a new FilePond instance and attach it to the input element
            const pond = FilePond.create(this.$refs.input, {
                acceptedFileTypes: @json($accepts())
            });
            // Listen for the file-upload-completed event from and remove all files
            addEventListener('file-upload-completed', e => {
                pond.removeFiles();
            });
            // Listen for the 'removefile' event
            pond.on('removefile', function(file) {
                // call the clearTmpUpload method on the CreateEdit component to clear
                // the tmpUpload property in the form. This is ugly but it works.
                @this.call('clearTmpUpload');
            });
        }
    </script>
@endpush
