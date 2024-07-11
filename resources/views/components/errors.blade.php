@if($errors->any())
    <div class="bx danger-light" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- @props(['type' => 'danger', 'iconClass' => ''])

@php
    $icon = match ($type) {
        'danger' => 'exclamation-triangle',
        'warning' => 'exclamation-circle',
        'info' => 'information-circle',
        'success' => 'check-circle',
        default => '',
    };
    $type = match ($type) {
        'danger' => 'danger-light',
        'warning' => 'warning-light',
        'info' => 'info-light',
        'success' => 'success-light',
        default => '',
    };

@endphp

<div @class(['bx bdr-2 rounded-075 flex va-c ' . $type])>
    <x-gt-icon name="{{ $icon }}" class="fs0 mr-075 {{ $iconClass }}" />
    {{ $slot }}
</div> --}}
