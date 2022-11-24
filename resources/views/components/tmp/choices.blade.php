@props([
    'for' => null,
    'value' => null,
    'placeholder' => null
    ])

    <x-gotime::input.control-group-layout>

        <select x-data x-init="function () { choices($el)}" multiple
            {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }} {{ $attributes }}>

            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif

            {{ $slot }}

        </select>


    </x-gotime::input.control-group-layout>

    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

            <script>
                window.choices = (element) => {

                    return new Choices(element, {
                        maxItemCount: 2,
                        removeItemButton: true,
                        shouldSort: true,
                        noResultsText: 'not found...',
                        placeholder: true,
                    });
                }
            </script>
        @endpush
    @endonce
