@aware(['for' => null, 'placeholder' => null])

<select {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has($for)]) }}>
    <option disabled selected value="">{{ $placeholder ?? 'Please select...' }}</option>
    {{ $slot }}
</select>
