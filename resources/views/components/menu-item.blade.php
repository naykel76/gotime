@props(['active', 'children' => null])

    @php
        $classes = ($active ?? false) ? 'active' : '';
    @endphp

    @if($children)

        <div class="dd">

            <a {{ $attributes->except('href')->merge(['class' => "$classes"]) }}>
                {{ $slot }}
                <svg class="icon sm">
                    <use href="/svg/naykel-ui-SVG-sprite.svg#down-caret"></use>
                </svg>
            </a>

            <div class="dd-content">
                <div class="menu light">
                    @foreach($children as $child)
                        <a href="{{ url($child->url) }}" class="{{ (request()->is("$child->url*")) ? 'active' : '' }}">{{ $child->title }}</a>
                    @endforeach
                </div>
            </div>

        </div>

    @else

        <a {{ $attributes->merge(['class' => "$classes"]) }}> {{ $slot }} </a>

    @endif
