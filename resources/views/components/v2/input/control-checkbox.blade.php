@aware(['for' => null, 'label'])

<label>
    <input {{ $attributes->merge(['checked' => old($for)]) }} name="{{ $for }}" type="checkbox" />
    {{ $slot->isNotEmpty() ? $slot : $label }}
</label>
