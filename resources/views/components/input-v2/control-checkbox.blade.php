@aware([ 'for' => null, 'label'])

<label>
    <input {{ $attributes }} name="{{ $for }}" type="checkbox" />
    {{ $slot->isNotEmpty() ? $slot : $label }}
</label>
