{{-- I am not convinced aware is the right choice here. The important thing is that the control gets
the editorId and config. This makes them props to the control but they will be passed down the
pipeline when creating the control group --}}
@aware(['editorId', 'config'])

@php
    // If config is passed, merge it with defaults. If not passed, fallback to defaults.
    $config = array_merge(
        [
            'editorType' => 'classic',
            'configType' => 'standard',
        ],
        $config ?? [],
    );
@endphp

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    if (!isset($wireModel)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }
@endphp

<x-gotime::input.partials.ckeditor-base>
    {{-- `@js()` safely converts a PHP variable into a JavaScript-friendly format. --}}
    <div x-data="editor('{{ $editorId }}', @js($config))" x-cloak>
        <div id="{{ $editorId }}" x-model="content" x-on:input.debounce.500ms></div>
    </div>
</x-gotime::input.partials.ckeditor-base>

@pushOnce('scripts')
    <script>
        const {
            BalloonEditor,
            ClassicEditor,
            basicEditorConfig,
            standardEditorConfig
        } = CKE;
        window.addEventListener('alpine:init', function() {
            Alpine.data('editor', (editorId, config) => ({
                content: @entangle($wireModel),
                editorElement: document.getElementById(editorId),
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
                setEditorConfig(config) {
                    return config === 'basic' ? basicEditorConfig : standardEditorConfig;
                },
                setEditorClass(editorType) {
                    return editorType === 'balloon' ? BalloonEditor : ClassicEditor;
                }
            }))
        });
    </script>
@endpushOnce
