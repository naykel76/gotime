@aware(['title'])

    <nav>

        <ul {{ $attributes }}>

            @isset($title)
                <li class="menu-title">{{ $title }}</li>
            @endisset

            {{ $slot }}

        </ul>

    </nav>
