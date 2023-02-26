<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;

class RouteCreator
{
    /**
     * Data for view
     */
    protected array $data = [];

    /**
     * object of menus from json file
     */
    protected array|object $menus;

    /**
     * when set to true nav.json files will be cached
     */
    protected bool $cache = false;

    public function __construct(
        protected string $filename,
        protected ?string $layout = null
    ) {
        $this->menus = $this->getMenusFromJson($filename);
        $this->data['navJsonFile'] = $filename;
        $this->data['menus'] =  $this->getMenuKeys($this->menus);
    }

    public function create(): void
    {
        foreach ($this->menus as $menu => $menuLinks) {

            foreach ($menuLinks->links as $item) {

                property_exists($item, 'children')
                    ? $this->createChildren($item->children)
                    : null;

                $this->make($item);
            }
        }
    }

    protected function createChildren(array|object $child): void
    {
        if (empty($item->exclude_route)) {
            foreach ($child as $item) {
                $this->make($item);
            }
        }
    }

    protected function make(array|object $item): void
    {

        if (empty($item->exclude_route)) {

            // if item `url` is null, generate one from the `route_name` structure
            $url = toUrl($item->route_name ?? $item->url);

            // unless the `view` attribute has been defined, the path to the blade
            // view will follow to the url or route structure, else use the `view`
            $view = empty($item->view) ?  $url : $item->view;

            // The file type to inject into a layout
            $this->data['type'] = ($item->type ?? null); // blade|md|null

            // $view in the context of a layout is the
            // path to the file to be injected NOT the
            // blade view itself.
            if ($this->layout) {
                $this->data['path'] = $view;
                $view = $this->layout;
            }

            // this could be a problem if mixed types ??
            if (isset($item->type)) {
                if ($item->type == 'md') {
                    $this->data['path'] = $view;
                    $view = 'layouts.markdown';
                }
            }

            $this->createRoute($url, $view, ($item->route_name ?? ''));
        };
    }


    private function createRoute($url, $view, $name = null): void
    {
        // NK::WTF for some reason you can not access $this->data['path']
        // inside the route function but this seems to make it go!
        $data = $this->data;

        Route::get($url, function () use ($data, $view) {
            return view($view)->with([$data, 'data' => $data]);
        })->name($name);
    }

    /**
     * Get the menu items from the json file
     */
    protected function getMenusFromJson(string $filename): object
    {
        return $this->cache
            ? cache()->remember($filename, 3600, fn () => getJsonFile(resource_path("navs/$filename.json")))
            : getJsonFile(resource_path("navs/$filename.json"));
    }

    /**
     * Get menu names (keys)
     */
    private function getMenuKeys(object $obj): array
    {

        $menuKeys = [];

        foreach ($obj as $menu => $menuLinks) {
            array_push($menuKeys, $menu);
        }

        return $menuKeys;
    }
}
