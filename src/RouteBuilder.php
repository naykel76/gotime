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
        $this->data['navFileName'] = $filename;
        $this->data['menus'] =  $this->getMenuKeys($this->menus);
    }

    public function create(): void
    {
        foreach ($this->menus as $menu => $menuLinks) {

            foreach ($menuLinks->links as $item) {

                // check if child routes should be created. This is
                // particularly useful when you are building menus
                if (!empty($item->create_child_routes)) {
                    property_exists($item, 'children')
                        ? $this->createChildLinks($item->children)
                        : null;
                }

                $this->make($item);
            }
        }
    }

    protected function createChildLinks(array|object $child): void
    {
        if (empty($child->exclude_route)) {
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

            // unless the `view` attribute has been defined, the creator will
            // attempt to resolve the view following the url structure
            $viewPath = empty($item->view) ?  $url : $item->view;

            // The file type to inject into a layout. This is only relevant
            // when the layout is set.
            $this->data['type'] = ($item->type ?? null); // blade|md|null

            // Manage single markdown page. NK?? What happens if layout is set?
            if (isset($item->type) && $item->type == 'md') {
                $this->data['path'] = $viewPath;
                $viewPath = 'layouts.markdown';
            }

            // $view in the context of a layout is the path to template
            if ($this->layout) {
                $this->data['path'] = $viewPath;
                $viewPath = $this->layout;
            }

            $this->createRoute($url, $viewPath, ($item->route_name ?? ''));
        };
    }

    private function createRoute($url, $view, $name = null): void
    {
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
        $file = getJsonFile(resource_path("navs/$filename.json"));

        return $this->cache ? cache()->remember($filename, 3600, fn () => $file) : $file;
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
