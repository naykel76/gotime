@aware([
    'editorId' => null,
    'editorType' => 'classic',
    'editorConfig' => 'standard',
    'componentName' => 'ckeditor control',
])

@php

    $wireModel = $attributes->whereStartsWith('wire:model')->first();

    if (!isset($wireModel)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }

    $config = [
        'editorId' => $editorId,
        'editorType' => $editorType,
        'configType' => $editorConfig,
    ];
@endphp

<x-gotime::.input.partials.ckeditor-base>
    <div x-data="editor($wire.entangle('{{ $wireModel }}'), @js($config))" x-cloak>
        <div id="{{ $editorId }}" x-model="content" x-on:input.debounce.500ms></div>
    </div>
</x-gotime::.input.partials.ckeditor-base>

@pushOnce('scripts')
    <script>
        const {
            BalloonEditor,
            ClassicEditor,
            InlineEditor,
            basicEditorConfigExt,
            standardEditorConfig
        } = CKE;
        window.addEventListener('alpine:init', function() {
            Alpine.data('editor', (content, config) => ({
                content: content,
                editorElement: document.getElementById(config.editorId),
                init() {
                    const editorConfig = this.setEditorConfig(config.configType);
                    const editorClass = this.setEditorClass(config.editorType);
                    this.initEditor(editorClass, editorConfig);
                },
                initEditor(editorClass, editorConfig) {
                    editorClass.create(this.editorElement, editorConfig)
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
                setEditorConfig(configType) {
                    switch (configType) {
                        case 'basic':
                            return basicEditorConfigExt;
                        default:
                            return standardEditorConfig;
                    }
                },
                setEditorClass(editorType) {
                    const editorClasses = {
                        balloon: BalloonEditor,
                        inline: InlineEditor,
                        classic: ClassicEditor
                    };
                    return editorClasses[editorType] || ClassicEditor;
                }
            }))
        });
    </script>
@endpushOnce
