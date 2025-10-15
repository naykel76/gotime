@aware(['menuTitle'])

<nav>
    <ul {{ $attributes }}>
        @if ($menuTitle)
            <li class="menu-title">{{ Str::upper($menuTitle) }}</li>
        @endif
        {{ $slot }}
    </ul>
</nav>
