{{-- Icons are not set up for this component so there is no need to use
    the `control-layout` --}}

@aware(['for' => null, 'placeholder' => null])

<select x-data x-init="function () { choices($el)}" multiple
    {{ $for ? "name=$for id=$for" : null }}
    {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>

    @if($placeholder)
        <option disabled selected value="">{{ $placeholder }}</option>
    @endif

    {{ $slot }}

</select>

{{-- how will this work with multiple instances? --}}
{{-- styles are included with jtb --}}
@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script>
            window.choices = (element) => {
                return new Choices(element, {
                    maxItemCount: 5,
                    removeItemButton: true,
                    shouldSort: true,
                    noResultsText: 'not found...',
                    placeholder: true,
                });
            }
        </script>
    @endpush
@endonce
