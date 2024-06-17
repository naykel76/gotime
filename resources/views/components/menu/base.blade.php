@aware(['title'])

<nav>
    <ul {{ $attributes }}>
        @if ($title)
            <li class="menu-title">{{ Str::upper($title) }}</li>
        @endif
        {{ $slot }}
    </ul>
</nav>
