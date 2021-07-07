{{---------------------------------------------------------------------------
    Reusable image selector component
    <x-image-picker for="image-path" :imagePath="$page->image_path ?? null"/>
---------------------------------------------------------------------------}}

{{-- add display block 'dblk' to make sure inline does not get in the way --}}

<div class="frm-row dblk tac">

    <image-picker image-path="{{ ! $imagePath ? asset('svg/placeholder.svg') : asset('storage/' . $imagePath) }}"></image-picker>
    
    <small>

        @error('image')
            <span class="txt-red" role="alert"> {{ $message }} </span>
        @enderror

        <strong>Max size 2MB</strong>

    </small>
</div>