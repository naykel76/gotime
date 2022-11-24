{{---------------------------------------------------------------------------
    Reusable Toolbar component used with edit-create blade template files

    usage:
    <x-media-toolbar form-name="document-form" />

    Hide preview button
    <x-media-toolbar formName="page-form" :preview=false />


    requires redirectById() helper
---------------------------------------------------------------------------}}


<div id="actions-toolbar" class="pxy-05 light flex">
    <div class="fg1">
        <button type="submit" form="{{ $formName }}" name="action" value="save_stay" class="btn success sm mr-025">
            <svg class="icon">
                <use xlink:href="/svg/naykel-ui-SVG-sprite.svg#save"></use>
            </svg>
            <span>SAVE</span>
        </button>
        <button type="submit" form="{{ $formName }}" name="action" value="save_close" class="btn success sm mr-025">
            <svg class="icon">
                <use xlink:href="/svg/naykel-ui-SVG-sprite.svg#exit"></use>
            </svg>
            <span>SAVE & CLOSE</span>
        </button>
        <button type="submit" form="{{ $formName }}" name="action" value="save_new" class="btn info sm mr-025">
            <svg class="icon">
                <use xlink:href="/svg/naykel-ui-SVG-sprite.svg#plus"></use>
            </svg>
            <span>SAVE & NEW</span>
        </button>

        <button type="submit" form="{{ $formName }}" name="action" value="save_preview" class="btn secondary sm" {{ $preview ? null : 'disabled' }}>
            <svg class="icon">
                <use xlink:href="/svg/naykel-ui-SVG-sprite.svg#preview"></use>
            </svg>
            <span>PREVIEW</span>
        </button>
    </div>
    <div class="right">
        <a href="{{ route("$routeName.index") }}" class="btn danger sm">
            <svg class="icon">
                <use xlink:href="/svg/naykel-ui-SVG-sprite.svg#back"></use>
            </svg>
            <span>BACK TO LIST</span>
        </a>
    </div>
</div>
