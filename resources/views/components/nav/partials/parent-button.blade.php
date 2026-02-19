@aware(['active', 'icon', 'iconType', 'iconClass'])
<button x-on:click="open = !open" :aria-expanded="open" {{ $active ? 'class="active"' : '' }}>
    <x-gotime::icon-label :$label :$icon :$iconType :class="$iconClass" />
    <x-gotime::chevron-toggle />
</button>