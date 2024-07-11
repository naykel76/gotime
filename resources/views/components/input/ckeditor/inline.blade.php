@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('This form control must have at least one of the following attributes specified: `wire:model` or `for`.');
    }
@endphp

<x-gotime::input.partials.ckeditor-layout>
    <div x-data="{ content: @entangle($attributes->wire('model')) }"
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
        <div name="{{ $for }}" id="{{ $editorId }}" x-model="content" x-on:input.debounce.500ms></div>
    </div>
</x-gotime::input.partials.ckeditor-layout>
