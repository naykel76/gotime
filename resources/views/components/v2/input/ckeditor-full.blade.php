@props([ 'for' => null, 'rowClass' => null, 'label' => null, 'req' => false, 'inline' => false, ])

    <div class='frm-row {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

        @isset($label)
            <label for="{{ $for }}">
                {{ Str::title($label) }} @if ($req) <span class='txt-red'>*</span> @endif </label>
        @endisset

        <div {{ $attributes->class(['w-full', 'bdr bdr-red' => $errors->has( $for )])->whereDoesntStartWith('wire:model') }}
            x-data="{ value: @entangle($attributes->wire('model')).defer }" x-cloak
            x-init="
                ClassicEditor

                    .create(document.querySelector('#{{ $editorId }}'),{
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
                            allow: [
                                {
                                    name: /.*/,
                                    attributes: true,
                                    classes: true,
                                    styles: true
                                }
                            ]
                        },
                        link: {
                            addTargetToExternalLinks: true,
                        }
                    })
                    .then(editor => {
                        if(value){
                            editor.setData(value);
                        }
                        editor.model.document.on('change:data', () => {
                            value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
        ">

            @isset($helpText)
                <div class="help mb-025 txt-muted"> <small>{{ $helpText }}</small> </div>
            @endisset

            <textarea name="{{ $for }}" id="{{ $editorId }}" x-model="value" x-on:input.debounce.500ms hidden></textarea>

        </div>
    </div>

    @pushOnce('scripts')
        <script src="{{ asset('js/ckeditor.js') }}"></script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            .ck .ck-content {
                min-height: 150px
            }
        </style>
    @endPushOnce
