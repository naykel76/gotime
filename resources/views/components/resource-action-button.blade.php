@props(['routePrefix', 'action', 'type' => 'button', 'icon' => null, 'text' => null, 'id' => null, 'slug' => null])

{{-- 
   This button component is used to create action buttons for actions like edit, show, delete, etc. 
   It is opinionated and is tightly coupled to Livewire components.
--}}

@php
    if (($action == 'edit' || $action == 'delete') && !isset($id)) {
        throw new InvalidArgumentException("An item ID must be provided for the $action action in the resource-action-button component.");
    }

    if ($type === 'link' && !isset($routePrefix)) {
        throw new InvalidArgumentException('You must provide a route prefix when using a link on the resource-action-button component.');
    }

    // if (!isset($id) && !isset($slug)) {
    //     throw new InvalidArgumentException('You must provide either an ID or a slug on the resource-action-button component.');
    // }

    // allows the icon to be set in the component
    if (!$icon) {
        $icon = match ($action) {
            'edit' => 'pencil-square',
            'show' => 'eye',
            'delete' => 'trash',
            'create' => 'plus-circle',
        };
    }

    $clickMethod = match ($action) {
        'create' => 'create',
        'delete' => "\$set('selectedItemId', $id)",
        'edit' => "edit({$id})",
        'save' => 'save',
    };
@endphp

@if ($type === 'link')
    <a href="{{ route("$routePrefix.$action", $id ?: $slug) }}" {{ $attributes->merge(['class' => 'action-button']) }}>
        <x-gt-icon name="{{ $icon }}" class="opacity-05" />
        @if ($text != '' || $slot->isNotEmpty())
            <span class="ml-025">{{ $slot->isNotEmpty() ? $slot : ($text != '' ? $text : '') }}</span>
        @endif
    </a>
@else
    {{-- In Blade components, attributes passed directly to the component can override those set
    within the component itself. This means that if you set the wire:click attribute directly when
    using the component, it will override the wire:click attribute here. --}}
    <x-gt-button.base wire:click="{{ $clickMethod }}" {{ $attributes->merge(['class' => 'action-button']) }}>
        <x-gt-icon name="{{ $icon }}" class="opacity-05" />
        @if ($text != '' || $slot->isNotEmpty())
            <span class="ml-025">{{ $slot->isNotEmpty() ? $slot : ($text != '' ? $text : '') }}</span>
        @endif
    </x-gt-button.base>
@endif
