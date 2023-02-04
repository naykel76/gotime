@props(['active', 'children' => null])

    @php
        $classes = ($active ?? false) ? 'active' : '';
    @endphp

    @if($children)

        <div class="dd">

            <a {{ $attributes->except('href')->merge(['class' => "$classes"]) }}>
                {{ $slot }}
                <x-gt-icon-down-caret class="icon pxy-05"/>
            </a>

            <div class="dd-content">
                <div class="menu light">
                    @foreach($children as $child)
                        <a href="{{ url($child->url) }}" class="{{ (request()->is("$child->url*")) ? 'active success' : '' }}">{{ $child->name }}</a>
                    @endforeach
                </div>
            </div>

        </div>

    @else

        <a {{ $attributes->merge(['class' => "$classes"]) }}> {{ $slot }} </a>

    @endif
