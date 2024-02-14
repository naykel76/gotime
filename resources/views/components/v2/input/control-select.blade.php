@aware(['for' => null, 'placeholder' => null])

<select {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has($for)]) }}>
    @unless ($attributes->has('multiple'))
        <option disabled selected value="">{{ $placeholder ?? 'Please select...' }}</option>
    @endunless
    {{ $slot }}
</select>
