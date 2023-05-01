@aware([ 'for' => null, 'label' => null, 'labelClass' => null, 'req' => false])

<label for="{{ $for }}" @if ($labelClass) class="{{ $labelClass }}" @endif>{{ Str::title($label) }}
    @if($req) <span class='txt-red'>*</span> @endif
</label>


