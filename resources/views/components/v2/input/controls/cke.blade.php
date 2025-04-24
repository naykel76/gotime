@aware(['editorId', 'editorType' => 'classic', 'editorConfig' => 'standard'])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();

    if (!isset($wireModel)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }

    $config = [
        'editorId' => $editorId,
        'editorType' => $editorType,
        'configType' => $editorConfig,
        'wireModel' => $wireModel,
    ];
@endphp

<x-gotime::v2.input.partials.ckeditor-base>
    <div x-data="editor({ ...@js($config), content: @entangle($attributes->wire('model')) })" x-cloak>
        <div id="{{ $editorId }}" x-model="content" x-on:input.debounce.500ms></div>
    </div>
</x-gotime::v2.input.partials.ckeditor-base>

@pushOnce('scripts')
    <script>
        window.addEventListener('alpine:init', function() {
            Alpine.data('editor', (config) => ({
                content: config.content,  // Accessing content from the passed config
                init() {
                    CKE.ClassicEditor
                        .create(document.getElementById(config.editorId), CKE.standardEditorConfig)
                        .then(editor => {
                            if (this.content) {
                                editor.setData(this.content);
                            }
                            editor.model.document.on('change:data', () => {
                                this.content = editor.getData();
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                },
            }))
        });
    </script>
@endpushOnce



{{-- To fix this, you should pass @entangle(...) into your Alpine component config directly as a
Blade-evaluated value, not compile it inside the script tag. Here's how to adjust it: --}}

{{-- <x-gotime::v2.input.partials.ckeditor-base>
    <div x-data="{ content: @entangle($attributes->wire('model')) }"
        x-init="CKE.ClassicEditor
            .create(document.querySelector('#{{ $editorId }}'), CKE.standardEditorConfig)
            .then(editor => {
                if (content) {
                    editor.setData(content);
                }
                editor.model.document.on('change:data', () => {
                    content = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });">
        <div id="{{ $editorId }}" x-model="content" x-on:input.debounce.500ms></div>
    </div>
</x-gotime::v2.input.partials.ckeditor-base> --}}

{{-- <div x-data="editor(@js(['editorId' => $editorId, 'wireModel' => $wireModel]))"> --}}

{{-- this works as expected where each editor has a unique ID and the content is correctly displayed in each editor --}}
{{--  --}}
