@props(['data'])

    {{-- is it better to have a single accordion that accepts a title and body, or an array with all items? --}}

    {{-- this will not be a one size fits all but it is a good starting point! --}}

    {{-- to collapse first item add ...  active: 0, --}}

    {{-- {{ dd($data) }} --}}

    <div class="accordion">
        <input type="checkbox" id="xyz">
        <label class="accordion-title" for="xyz"> ... </label>
        <div class="accordion-content">
            <p> ... </p>
        </div>
    </div>