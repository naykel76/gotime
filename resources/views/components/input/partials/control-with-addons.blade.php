@aware(['leadingAddon', 'trailingAddon'])

@if ($leadingAddon || $trailingAddon)
    <div class="with-addons">
        @if ($leadingAddon)
            <div {{ $leadingAddon->attributes->class(['leading-addon']) }}>{{ $leadingAddon }}</div>
        @endif
        {{-- trailing addon must come before slot for JTB to manage borders correctly --}}
        @if ($trailingAddon)
            <div {{ $trailingAddon->attributes->class(['trailing-addon']) }}>{{ $trailingAddon }}</div>
        @endif
        {{ $slot }}
    </div>
@else
    {{ $slot }}
@endif
