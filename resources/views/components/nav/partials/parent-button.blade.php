@aware(['active', 'icon', 'item'])
<button x-on:click="open = !open" {{ $active ? 'class="active"' : '' }}>
    <x-gotime::icon-label :$label :$icon />
    <x-gotime::chevron-toggle />
</button>