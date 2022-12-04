@props([ 'for' => null, 'label' => null, 'helpText' => null, 'rowClass' => null, 'inline' => false, 'req' => false, 'initialValue' => null, ])

{{-- @php
    // replace dot to make sure query selector id is correct
    $for = str_replace('.', '-', $for);
@endphp --}}


<div class='frm-row {{ $inline ? 'inline' : '' }} {{ $rowClass }}'>

    @isset($label)
        <label @error($for) class="txt-red fw9 " @enderror for="{{ $for }}">
            {{ Str::title($label) }} @if ($req) <span class='txt-red'>*</span> @endif </label>
    @endisset

    {{-- NK::?? the prevents the error class working when in line?? --}}
    @unless ($inline)
        @error($for)
            <small class="txt-red" role="alert"> {{ $message }} </small>
        @enderror
    @endunless

    <div wire:ignore x-cloak {{ $attributes->class(['fullwidth', 'bdr-3 bdr-red' => $errors->has( $for )]) }}
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

</div>

@once('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
@endonce

@push('styles')

    <style>
        .ck .ck-content{
            min-height: 250px
        }
    </style>

@endpush
