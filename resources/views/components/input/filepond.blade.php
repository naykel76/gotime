{{-- *** COMPONENT BASED CLASS *** --}}

<div x-data="{ initFilePond, loading: false }" wire:ignore x-init="initFilePond()">
    <input type="file" x-ref="input" style="display:none">
    <x-gt-loading-indicator x-show="loading" />
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

            FilePond.setOptions({
                allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
                maxFileSize: '{{ $maxFileSize }}KB',
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes['wire:model'] }}', file,
                            (uploadedFilename) => {
                                load(uploadedFilename);
                            },
                            (e) => {
                                error(e);
                            },
                            (event) => {
                                progress(event.loaded, event.total);
                            }
                        )
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

            // Show loader on start
            pond.on('addfilestart', () => {
                this.loading = true;
            });

            // Hide loader when file is processed
            pond.on('processfile', () => {
                this.loading = false;
            });

            // Also hide on error or abort
            pond.on('processfileabort', () => {
                this.loading = false;
            });

            pond.on('processfileerror', () => {
                this.loading = false;
            });

            // Clear files when Livewire tells us upload is complete
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
