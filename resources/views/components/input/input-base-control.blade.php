@aware(['leadingAddon', 'trailingAddon', 'for' => null, 'value' => null])

    @if($leadingAddon || $trailingAddon)

        <div class="withAddons">

            @if($leadingAddon)
                <div {{ $leadingAddon->attributes->class(['leadingAddon']) }}>{{ $leadingAddon }}</div>
            @endif

            <input {{ $for ? "name=$for id=$for" : null }}
                @class([
                    'bdrr-l-0 bdr-l-0' => !$trailingAddon,
                    'bdrr-r-0 bdr-r-0' => !$leadingAddon,
                    'bdrr-0 bdr-x-0' => ($leadingAddon && $trailingAddon)
                ])
                {{ $attributes->class([ 'bdr-red' => $errors->has( $for ) ]) }}
                @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif
                />

                @if($trailingAddon)
                    <div {{ $trailingAddon->attributes->class(['trailingAddon']) }}>{{ $trailingAddon }}</div>
                @endif

        </div>

    @else

        <input {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class([ 'bdr-red' => $errors->has( $for ) ]) }}
            @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif
        />

    @endif
