@aware(['editorId'])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    if (!isset($wireModel)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }
@endphp

<x-gotime::input.partials.ckeditor-base>
    <div x-data="{ content: @entangle($attributes->wire('model')) }" x-cloak
        x-init="CKEDITOR.ClassicEditor
            .create(document.querySelector('#{{ $editorId }}'), {
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                    ]
                },
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                link: {
                    addTargetToExternalLinks: true,
                }
            })
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
