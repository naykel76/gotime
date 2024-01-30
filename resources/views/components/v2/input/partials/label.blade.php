@aware([
    'for' => null,
    'label' => null,
    'labelClass' => null,
    'req' => false,
    'tooltip' => false,
    ])

    @if($tooltip)
        <div class="flex va-c space-between mb-025">
    @endif

    <label for="{{ $for }}"  {{ $attributes->except('tooltip') }}>
        {{ Str::title($label) }}
        @if($req) <span class='txt-red'>*</span> @endif
    </label>

    @if($tooltip)
        <x-gt-tooltip> {{ $tooltip }} </x-gt-tooltip>
        </div>
    @endif


