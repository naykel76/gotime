@aware([ 'leadingAddon', 'trailingAddon', 'for' => null, 'value' => null])

@if($leadingAddon || $trailingAddon)

    <div class="with-addons">

        <input {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}
            @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif
        />

        @if($leadingAddon)
            <div class="leadingAddon">{{ $leadingAddon }}</div>
        @endif

        @if($trailingAddon)
            <div class="trailingAddon">{{ $trailingAddon }}</div>
        @endif

    </div>

@else

    <input {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}
        @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif
    />

@endif


