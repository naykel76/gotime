<span x-data="{open:false}" x-on:mouseenter="open=true" x-on:mouseleave="open=false" class="relative">
    <x-gt-icon-help class="txt-muted" />
    <div class="absolute pos-r w-24 z-100 bx info-light pxy-075 mt-05 txt-sm fw4" x-show="open" x-transition.duration style="display: none;">
        {{ $slot }}
    </div>
</span>