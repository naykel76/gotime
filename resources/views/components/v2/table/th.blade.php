@props([
    'sortable' => null,
    'direction' => null,
    'alignCenter' => false,
    'alignRight' => false,
    'alignLeft' => false,
])

{{-- convert to upper case unless txt-capitalize or txt-lower class is present --}}
@php
    $text = Str::contains($attributes->get('class'), 'txt-capitalize') ? $slot : (Str::contains($attributes->get('class'), 'txt-lower') ? $slot : Str::upper($slot));
@endphp

<th {{ $attributes }}>
    @if ($sortable)
        <button @class([
            'w-full tal',
            'tal' => $alignLeft,
            'tar' => $alignRight,
            'tac' => $alignCenter,
        ])>
            <span>{{ $text }}</span>
            @if ($direction === 'asc')
                <svg class="icon txt-primary" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="m12.191 15.865 4.973-6.321c.13-.163.211-.39.211-.634 0-.488-.325-.91-.715-.91H6.715C6.325 8 6 8.406 6 8.91c0 .244.081.471.211.634l4.973 6.321a.65.65 0 0 0 .504.26c.194 0 .357-.098.503-.26Z" />
                </svg>
            @elseif($direction === 'desc')
                <svg class="icon txt-primary" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.184 8.26 6.21 14.581c-.13.163-.211.39-.211.634 0 .488.325.91.715.91h9.945c.39 0 .715-.406.715-.91 0-.244-.081-.471-.211-.634L12.19 8.26a.647.647 0 0 0-.504-.26c-.194 0-.357.098-.503.26Z" />
                </svg>
            @else
                <svg class="icon txt-muted" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="m12.191 20.865 4.973-6.321c.13-.163.211-.39.211-.634 0-.488-.325-.91-.715-.91H6.715c-.39 0-.715.406-.715.91 0 .244.081.471.211.634l4.973 6.321c.13.163.309.26.504.26.194 0 .357-.098.503-.26ZM11.184 2.26 6.21 8.581c-.13.163-.211.39-.211.634 0 .488.325.91.715.91h9.945c.39 0 .715-.406.715-.91 0-.244-.081-.471-.211-.634L12.19 2.26a.647.647 0 0 0-.504-.26c-.194 0-.357.098-.503.26Z" />
                </svg>
            @endif
        </button>
    @else
        <span>{{ $text }}</span>
    @endif
</th>
