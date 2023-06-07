@aware(['leadingAddon', 'trailingAddon', 'for' => null])

    @if($leadingAddon || $trailingAddon)

        <div class="withAddons">

            @if($leadingAddon)
                <div {{ $leadingAddon->attributes->class(['leadingAddon']) }}>{{ $leadingAddon }}</div>
            @endif

            {{ $slot }}

            @if($trailingAddon)
                <div {{ $trailingAddon->attributes->class(['trailingAddon']) }}>{{ $trailingAddon }}</div>
            @endif

        </div>

    @else

        {{ $slot }}

    @endif
