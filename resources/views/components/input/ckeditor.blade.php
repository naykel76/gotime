@props(['for' => $for])

<div class="frm-row" wire:ignore
    x-data="{ value: @entangle($attributes->wire('model')) }"
    x-init="
        ClassicEditor.create(document.querySelector('#ckeditor'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                @this.set('{{ $for }}', editor.getData());
            })
        })
        .catch(error => {
            console.error(error);
        });"
>
    <textarea name="{{ $for }}" id="ckeditor" x-text="value">  </textarea>

</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
@endpush
