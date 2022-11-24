@props([ 'isPrimary' => true, 'text' => 'Submit', 'rowClass' => null])

{{-- NK::TD this needs to be refactored to include frm-row, maybe even move to buttons ?? --}}

<div class='frm-row {{ $rowClass }}'>

    <button type="submit" {{ $attributes->merge(['class' => 'btn' . ($isPrimary ? ' primary' : null)]) }}> {{ $text }} </button>

</div>
