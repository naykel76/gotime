@props([ 'for' => null, 'rowClass' => null, 'label' => null, 'req' => false, 'inline' => false, ])

    <div class='frm-row {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

        @isset($label)
            <label for="{{ $for }}">
                {{ Str::title($label) }} @if ($req) <span class='txt-red'>*</span> @endif </label>
        @endisset

        <div {{ $attributes->class(['w-full', 'bdr bdr-red' => $errors->has( $for )])->whereDoesntStartWith('wire:model') }}
            x-data="{ value: @entangle($attributes->wire('model')) }" x-cloak
            x-init="
                ClassicEditor.create(document.querySelector('#{{ $editorId }}'))
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
        <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            .ck .ck-content {
                min-height: 150px
            }
        </style>
    @endPushOnce
