<div class="flex space-x-2">

    <x-gt-input wire:model="search" controlOnly for="search" placeholder="Search {{ $searchField }}..." />

    <div class="flex va-c">
        <label class="mr-075">Search By</label>
        <x-gt-select wire:model="searchField" for="searchField" controlOnly>
            @foreach($searchOptions as $key => $value)
                <option value="{{ $key }}">{{ Str::title($value) }}</option>
            @endforeach
        </x-gt-select>
    </div>

    <div class="flex va-c">
        <label class="mr-075">Items per page: </label>
        <x-gt-select wire:model="perPage" for="perPage" controlOnly>
            @foreach($paginateOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </x-gt-select>
    </div>

    {{ $slot }}

</div>
