@props([
    'legend' => null,
    'for' => null,
    'options' => [],
    'groupClass' => 'space-y-05',
])

<fieldset class="frm-row {{ $groupClass }}">
    @if ($legend)
        <legend>{{ $legend }}</legend>
    @endif
    
    @if (!empty($options))
        @foreach ($options as $value => $label)
            <x-gotime::input.checkbox :for="$for" :value="$value" :label="$label" />
        @endforeach
    @else
        {{ $slot }}
    @endif
</fieldset>
