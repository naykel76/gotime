<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;

class RouteBuilder
{
    /**
     * Data for view
     */
    protected array $data = [];

    /**
     * object of menus from json file
     */
    protected object $menus;

    /**
     *
     */
    protected bool $cache = false;

    public function create(string $filename, string $layout = null): void
    {
        $this->menus = $this->setMenusFromJson($filename);
        $this->layout = $layout;

        $this->data['navJsonFile'] = $filename;
        $this->data['menus'] =  $this->getMenuKeys($this->menus);

        foreach ($this->menus as $menu => $menuLinks) {

            foreach ($menuLinks->links as $item) {

                property_exists($item, 'children')
                    ? $this->createChildren($item->children)
                    : null;

                $this->make($item);
            }
        }
    }

    protected function createChildren(array|object $items): void
    {
        if (empty($item->exclude_route)) {
            foreach ($items as $item) {
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

            $this->buildRoute($url, $view, ($item->route_name ?? ''));
        };
    }


    private function buildRoute($url, $view, $name = null)
    {
        // NK::WTF for some reason you can not access $this->data['path']
        // inside the route function but this seems to make it go!
        $data = $this->data;

        Route::get($url, function () use ($data, $view) {
            return view($view)->with([$data, 'data' => $data]);
        })->name($name);
    }

    /**
     * Get the menu items for a json file
     */
    protected function setMenusFromJson(string $filename): object
    {
        return $this->cache
            ? cache()->remember($filename, 3600, fn () => getJsonFile(resource_path("navs/$filename.json")))
            : getJsonFile(resource_path("navs/$filename.json"));
    }
    /**
     * Get menu names (keys)
     *
     * @param object $obj
     * @return array
     */
    private function getMenuKeys(object $obj): array
    {

        $menus = [];

        foreach ($obj as $menu => $menuLinks) {
            array_push($menus, $menu);
        }

        return $menus;
    }
}
