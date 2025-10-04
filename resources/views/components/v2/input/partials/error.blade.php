@props(['for'])

@error($for)
    <p class="mt-025 txt-xs txt-red-600" role="alert">
        {{ $message }}
    </p>
@enderror