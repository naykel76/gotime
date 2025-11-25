@props(['for'])

@error($for)
    <p id="{{ $for }}-error" class="flex va-c gap-05 mt-025 txt-sm txt-red-500" role="alert">
        <x-gt-icon name="exclamation-triangle" class="wh-1.25" />
        {{ $message }}
    </p>
@enderror