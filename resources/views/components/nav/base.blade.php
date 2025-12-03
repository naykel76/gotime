{{-- base file --}}
@aware(['menuTitle'])

<nav {{ $attributes }}>
    @if ($menuTitle)
        <div class="menu-title">{{ Str::upper($menuTitle) }}</div>
    @endif
   
    {{ $slot }}
</nav>


