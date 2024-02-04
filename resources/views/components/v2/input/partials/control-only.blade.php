@aware(['leadingAddon', 'trailingAddon', 'for' => null])

@if ($leadingAddon || $trailingAddon)
    <div class="withAddons">
        @if ($leadingAddon)
            <div {{ $leadingAddon->attributes->class(['leadingAddon']) }}>{{ $leadingAddon }}</div>
        @endif
        {{-- trailing addon must come before slot for JTB to manage borders correctly --}}
        @if ($trailingAddon)
            <div {{ $trailingAddon->attributes->class(['trailingAddon']) }}>{{ $trailingAddon }}</div>
        @endif
        {{ $slot }}
    </div>
@else
    {{ $slot }}
@endif
