{{-- NK::TD there is some glitching when there are errors! --}}
@push('styles')
    {{-- <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"> --}}
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    <style>
        /* .filepond--item {
            width: calc(25% - 0.5em);
        } */
        #nk-main .filepond--root ul{
            list-style: none;
        }
    </style>
@endpush

@props(['for' => null])

<div
    wire:ignore
    x-data="{pond: null}"
    x-init="
        {{-- FilePond.registerPlugin(FilePondPluginImagePreview); --}}
        pond = FilePond.create($refs.input);
        pond.setOptions({
            allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress)
                },
                revert: (filename, load) => {
                    @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                },
            },
        });
        this.addEventListener('pondReset', e => {
            pond.removeFiles();
        });
    ">
        <input type="file" name="{{ $for }}" x-ref="input" style="display:none">

    </div>

    @error($for)
        <small class="txt-red" role="alert"> {{ $message }} </small>
    @enderror

    @once
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
        {{-- <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script> --}}
    @endonce

