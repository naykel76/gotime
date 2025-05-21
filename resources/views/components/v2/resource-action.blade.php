@props([
    'action', // required: create, edit, delete, etc
    'routePrefix' => null,
    'slug' => null,
    'id' => null,
    'icon' => null,
    'iconOnly' => false,
    'text' => null,
])

@php
    if (($action == 'edit' || $action == 'delete') && !isset($id) && !isset($slug)) {
        throw new InvalidArgumentException("An item ID or slug must be provided for the $action action in the resource-action component.");
    }

    $text ??= ucfirst($action);

    if (!$icon) {
        $icon = match ($action) {
            'create' => 'plus-circle',
            'delete' => 'trash',
            'edit' => 'pencil-square',
            'view' => 'eye',
            'show' => 'eye',
        };
    }

    $class = match ($action) {
        'create' => 'txt-sky-600',
        'delete' => 'txt-red-600',
        'edit' => 'txt-orange-600',
        'view' => 'txt-gray-600',
        'show' => 'txt-gray-600',
    };

    $clickMethod = match ($action) {
        'create' => 'create',
        'delete' => "\$set('selectedId', $id)",
        'edit' => "edit({$id})",
        'save' => 'save',
        default => 'view',
    };
@endphp

{{-- if there is a route prefix, then you can assume we are using a route --}}
@if (isset($routePrefix))
    <a href="{{ route("$routePrefix.$action", $id ?: $slug) }}" {{ $attributes->class([$class, 'action-button']) }}>
        <x-gt-icon name="{{ $icon }}" class="wh-1" />
        @unless ($iconOnly)
            <span class="ml-025 fw6">{{ $slot->isNotEmpty() ? $slot : $text }}</span>
        @endunless
    </a>
@else
    <x-gt-button.base wire:click="{{ $clickMethod }}" {{ $attributes->class([$class, 'action-button']) }}>
        <x-gt-icon name="{{ $icon }}" class="wh-1" />
        @unless ($iconOnly)
            <span class="ml-025 fw6">{{ $slot->isNotEmpty() ? $slot : $text }}</span>
        @endunless
    </x-gt-button.base>
@endif

@push('styles')
    <style>
        .action-button {
            display: inline-flex;
            align-items: center;
        }

        .action-button:hover {
            text-decoration: underline;
        }
    </style>
@endpush




