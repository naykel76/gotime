@props(['for' => null, 'label'])

<x-gt-control-group>

    {{--
        The `x-data="{ initFilePond }"` making the `initFilePond` function
        available within the component's scope. The `x-init` directive is used to
        run the `initFilePond` function when the component is initialized.
    --}}

    <div x-data="{ initFilePond }" wire:ignore x-init="initFilePond()">
        <input type="file" x-ref="input" style="display:none">
    </div>

</x-gt-control-group>



@push('styles')
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <style>
        .filepond--root {
            margin-bottom: 0;
        }

        .filepond--credits {
            display: none;
        }
    </style>
@endpush

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
                maxFileSize: '3MB',
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
                acceptedFileTypes: ['image/jpeg', 'image/jpg', 'image/gif', 'image/png']
            });
            // Listen for pondReset event and remove all files
            addEventListener('pondReset', e => {
                pond.removeFiles();
            });
            // Listen for the 'removefile' event
            pond.on('removefile', function(file) {
                @this.set('tmpUpload', null);
            });
        }
    </script>
@endpush
