<p align="center"><a href="https://naykel.com.au" target="_blank"><img src="https://avatars0.githubusercontent.com/u/32632005?s=460&u=d1df6f6e0bf29668f8a4845271e9be8c9b96ed83&v=4" width="120"></a></p>

<a href="https://packagist.org/packages/naykel/gotime"><img src="https://img.shields.io/packagist/dt/naykel/gotime" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/naykel/gotime"><img src="https://img.shields.io/packagist/v/naykel/gotime" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/naykel/gotime"><img src="https://img.shields.io/packagist/l/naykel/gotime" alt="License"></a>

# NAYKEL Gotime

Starter package for NayKel Laravel applications.

## Installation

To get started, install Gotime using the Composer package manager:

    composer require naykel/gotime

Next, install Gotime resources using the gotime:install command:

    php artisan gotime:install

The configuration files are merged when the package is registered, however you can optionally publish the `naykel.php` configuration file.

    php artisan vendor:publish --tag=gotime-config

## Known Issues

**This driver does not support creating temporary URLs.**

https://laracasts.com/discuss/channels/livewire/pdf-passes-image-validation?page=1&replyId=806087

https://github.com/livewire/livewire/discussions/3133#discussioncomment-2741258

    if (! $this->isPreviewable()) {
        // show a missing image icon (?) for files that cannot be previewed
        return 'data:image/png;base64...gg-1.5==';
    }



### Mount the resources or create a blank model

Depending on the route, the Livewire component will either mount a resource and set `$editing` with route model binding or create a new blank model setting initial values from the `$initalValues` array.


### Adding the main image

The `$tmpImage` variable is set in the trait as there will be no need to manually use it. ??

The image paramt

2. Set the `$disk` or leave blank for `public`  ????

Defined attributed in the trait can be reset or overrider in the `mount()` method of the main component


## Things I learned the Hard Way

Do not define a variable data type that is a file as a `string`. Why? It's a file, not a string!


## CkEditor

    <div wire:ignore class="frm-row">
        <textarea wire:model.lazy="editing.description" name="editing.description" id="ckeditor"></textarea>
    </div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#ckeditor'))
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('editing.description', editor.getData());
                })
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    @endpush


## Adding New Icons

**For consistency:**

- icons should be saved 20 high
- icons should not have any fill color

##### Step 1. Save SVG files in `resources/views/components/icon`.

##### Step 2. change extension from `svg` to `blade.php`

You can batch update the file extensions by running the following command in the `resources/views/components` directory.

```bash
for i in *.svg; do mv -- "$i" "${i%.svg}.blade.php"; done

find ./resources/views/components/icon -type f -name "*.svg" -exec bash -c 'mv -- "$1" "${1%.svg}.blade.php"' _ {} \;

# this is pretty wild, and updates regardless
find ./resources/views/components/icon -type f -name "*.blade.php" -exec sed -i 's/<svg xmlns="http:\/\/www\.w3\.org\/2000\/svg"/<svg {{ $attributes }} xmlns="http:\/\/www\.w3\.org\/2000\/svg"/g' {} +

# this should only update if there is a change
find ./resources/views/components/icon -type f -name "*.blade.php" -exec grep -q '<svg xmlns="http://www.w3.org/2000/svg"' {} \; -exec sed -i 's/<svg xmlns="http:\/\/www\.w3\.org\/2000\/svg"/<svg {{ $attributes }} xmlns="http:\/\/www.w3.org\/2000\/svg"/g' {} +
```

##### Step 3. Remove fill color

Using the editor find and replace, remove the fill colour to allow default styles to work.


##### Step 4. Add $attributes

Search for

    <svg xmlns="http://www.w3.org/2000/svg"

Replace with

    <svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg"
