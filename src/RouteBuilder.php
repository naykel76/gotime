<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;

class RouteBuilder
{

    protected array $data = [];

    public function create(string $filename, string $layout = null)
    {

        $navObject = fetchJsonFile("navs/$filename.json");

        $this->data['navJsonFile'] = $filename;
        $this->data['menus'] =  $this->getMenuKeys($navObject);

        foreach ($navObject as $menu => $menuLinks) {

            foreach ($menuLinks->links as $item) {

                if (empty($item->exclude_route)) {

                    // convert route name to url if exists, else use url
                    $url = sanitizeUrlPath($item->route_name ?? $item->url);

                    // The path to the view file.If exists in json
                    // use it, else default to url path
                    $view = sanitizeUrlPath(($item->view ?? $url));

                    // The file type to inject into a layout
                    $this->data['type'] = ($item->type ?? null); // blade|md|null

                    // $view in the context of a layout is the
                    // path to the file to be injected NOT the
                    // blade view itself.
                    if ($layout) {
                        $this->data['path'] = $view;
                        $view = $layout;
                    }

                    // this could be a problem if mixed types ??
                    if (isset($item->type)) {
                        if ($item->type == 'md') {
                            $this->data['path'] = $view;
                            $view = 'layouts.markdown';
                        }
                    }

                    $this->buildRoute($url, $view, ($item->route_name ?? null));

                };
            }
        }
    }

    private function buildRoute($url, $view, $name = null)
    {
        // separating this function allow greater flexibility and
        // control of the data array

        $data = $this->data;

        Route::get($url, function () use ($data, $view) {
            return view($view)->with([$data, 'data' => $data]);
        })->name($name);
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
