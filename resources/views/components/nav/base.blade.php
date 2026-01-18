@aware(['menuTitle', 'excludeWrapper'])

@if ($excludeWrapper ?? false)
    @if ($menuTitle)
        <div class="menu-title">{{ Str::upper($menuTitle) }}</div>
    @endif
   
    {{ $slot }}
@else
    <nav {{ $attributes }}>
        @if ($menuTitle)
            <div class="menu-title">{{ Str::upper($menuTitle) }}</div>
        @endif
       
        {{ $slot }}
    </nav>
@endif


