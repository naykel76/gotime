@props([
    'action', // required: create, edit, delete, etc
    'routePrefix' => null,
    'slug' => null,
    'id' => null,
    'icon' => null,
    'iconOnly' => false,
    'text' => null,
    'dispatchTo' => null, // target component for dispatchTo
    'dispatch' => null, // event name for global dispatch
])

@php
    // Only require ID if using default behavior (no custom wire:click provided)
    if (($action == 'edit' || $action == 'delete') && !isset($id) && !isset($slug) && !$attributes->has('wire:click')) {
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

    /**
     * Build the Livewire click method string based on action and dispatch options.
     */
    if (!function_exists('buildClickMethod')) {
        function buildClickMethod(string $action, $id = null, ?string $dispatchTo = null, ?string $dispatch = null): string
        {
            // Handle dispatch cases (component-to-component or global events)
            if ($dispatchTo || $dispatch) {
                $eventName = $action . '-model'; // e.g., 'create-model', 'edit-model'

                // Only edit and delete actions need ID parameters
                $params = in_array($action, ['edit', 'delete']) && $id ? ", { id: $id }" : '';

                // Return dispatchTo or dispatch method string
                return $dispatchTo ? "\$dispatchTo('$dispatchTo', '$eventName'$params)" : "\$dispatch('$dispatch'$params)";
            }

            // Default behavior: direct method calls or Livewire actions
            return match ($action) {
                'create' => 'create', // Call create() method
                'delete' => "\$set('selectedId', $id)", // Set selectedId for confirmation
                'edit' => "edit({$id})", // Call edit() method with ID
                'save' => 'save', // Call save() method
                default => 'view', // Default to view() method
            };
        }
    }

    // Generate the wire:click method string
    $clickMethod = buildClickMethod($action, $id, $dispatchTo, $dispatch);

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
