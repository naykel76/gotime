<nav {{ $attributes }}>
    <ul>
        @if ($title)
            <div class="menu-title">{{ Str::upper($title) }}</div>
        @endif
        {{ $slot }}
    </ul>
</nav>
