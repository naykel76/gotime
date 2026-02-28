@props(['opened' => false, 'title'])

<div x-data="{ open: @json($opened) }" class="bg-white rounded-lg shadow-sm bdr bdr-gray-200">
    <button type="button" x-on:click="open = !open" class="w-full px-1.5 py-1 flex items-center justify-between hover:bg-gray-50">
        <span class="font-semibold txt-gray-900">{{ $title }}</span>
        <svg class="wh-1" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" x-collapse class="px-1.5 py-1 mxy-0 txt-gray-600">
        {{ $slot }}
    </div>
</div>