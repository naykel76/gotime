@aware(['active', 'icon', 'iconType', 'item'])
<button x-on:click="open = !open" :aria-expanded="open" {{ $active ? 'class="active"' : '' }}>
    <x-gotime::icon-label :$label :$icon :$iconType />
    <x-gotime::chevron-toggle />
</button>