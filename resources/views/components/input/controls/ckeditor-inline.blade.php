@aware(['editorId'])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    if (!isset($wireModel)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }
@endphp

<x-gotime::input.partials.ckeditor-base>
    <div x-data="{ content: @entangle($wireModel) }"
        x-init="CKEDITOR.InlineEditor
            .create(document.querySelector('#{{ $editorId }}'))
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
</x-gotime::input.partials.ckeditor-base>
