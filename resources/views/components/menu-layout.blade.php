@aware(['title'])

<nav>

    <ul {{ $attributes }}>

        @if($title)
            <li class="menu-title">{{ $title }}</li>
        @endif

        {{ $slot }}

    </ul>

</nav>
