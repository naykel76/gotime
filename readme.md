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
        <textarea wire:model.blur="editing.description" name="editing.description" id="ckeditor"></textarea>
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


1. update extension `svg` to `blade.php`
```bash
find ./resources/views/components/v2/icon -name "*.svg" -type f -exec bash -c 'mv -- "$0" "${0%.svg}.blade.php"' {} \;
```

1. add `$attributes`, `width` and `height`
```bash
find ./resources/views/components/v2/icon -type f -name "*.blade.php" -exec sed -i 's/<svg xmlns="http:\/\/www\.w3\.org\/2000\/svg"/<svg {{ $attributes }} xmlns="http:\/\/www\.w3\.org\/2000\/svg" width="24" height="24"/g' {} +
```
