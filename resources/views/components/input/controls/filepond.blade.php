{{-- pass the Livewire component instance to the initFilePond function so it can interact with Livewire --}}
@php($acceptedTypes = $accepts())
<div x-data="{ initFilePond, loading: false }" wire:ignore x-init="initFilePond($wire)">
    <input type="file" x-ref="input" style="display:none" @if ($acceptedTypes) accept="{{ implode(',', $acceptedTypes) }}" @endif>
</div>

@push('scripts')
    <script>
        function initFilePond(livewireComponent) {

            const $wire = livewireComponent;

            // Create FilePond instance with ALL options passed directly to create()
            const pond = FilePond.create(this.$refs.input, {
                // Allow multiple files if the Blade component attribute 'multiple' is present
                allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},

                // Max file size (in Kilobytes) passed from the Blade component
                maxFileSize: '{{ $maxFileSize }}MB',

                // Allowed MIME types (retrieved from the Blade component)
                acceptedFileTypes: @json($acceptedTypes),

                // Configure FilePond to use Livewire's built-in upload and revert handlers
                server: {
                    // Define the handler when a file is ready to be uploaded
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        // Use Livewire's $wire.upload() method for handling temporary file uploads
                        $wire.upload(
                            '{{ $attributes['wire:model'] }}', // The Livewire component property (e.g., 'public $photo') to bind the upload to
                            file,

                            // On success of the Livewire upload, call FilePond's load method
                            (uploadedFilename) => {
                                load(uploadedFilename);
                                // $wire.dispatch('file-uploaded');
                            },

                            // On error
                            (e) => {
                                error(e);
                            },

                            // Upload progress
                            (event) => {
                                progress(event.loaded, event.total);
                            }
                        )
                    },

                    // Define the handler when FilePond removes a file from the UI
                    revert: (filename, load) => {
                        $wire.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                    },
                }
            });

            // Show a loader when a file starts processing
            pond.on('addfilestart', () => {
                this.loading = true;
            });

            // Hide loader once processed
            pond.on('processfile', () => {
                this.loading = false;
            });

            // Hide loader on abort
            pond.on('processfileabort', () => {
                this.loading = false;
            });

            // Hide loader on error
            pond.on('processfileerror', () => {
                this.loading = false;
            });

            // When Livewire says the upload is complete, clear FilePond UI
            addEventListener('file-upload-completed', (e) => {
                pond.removeFiles();
            });

            // When a user removes a file from FilePond
            pond.on('removefile', (file) => {
                // Call a method on the Livewire component to clear any temporary data on the backend
                $wire.call('clearTmpUpload');
            });
        }
    </script>
@endpush
