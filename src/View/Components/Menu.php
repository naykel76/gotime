<?php

namespace Naykel\Gotime\View\Components;

use Exception;
use Illuminate\View\Component;

class Menu extends Component
{

    /**
     * nav.json file
     */
    public object $file;

    public function __construct(
        public string $filename = "nav-main",   // name of json file in navs directory
        public string $menuname = '',           // specific menu from json file
        public bool $withIcons = false,         // gotime icon name
        public bool $withHeaders = false,       // display menu header
        public string $itemClass = '',
    ) {
        $this->file = getJsonFile(resource_path("navs/$this->filename.json"));
    }

    public function render()
    {
        return $this->menuname
            ?  view('gotime::components.menu')->with(['menu' => $this->getMenu($this->menuname)])
            : view('gotime::components.menus')->with(['file' => $this->file]);
    }

    /**
     * Check if the current route is active
     */
    public function isActive($item)
    {

        if (isset($item->route_name)) {
            return request()->routeIs("$item->route_name*");
        } elseif (isset($item->url)) {
            return request()->is($item->url);
        }
    }

    /**
     * Get the specified menu from the nav object
     */
    public function getMenu($menuname): object
    {
        return $this->file->$menuname;
    }

    /**
     * Create url from item route name or url
     */
    public function getUrl($item): string
    {
        if (!isset($item->route_name) && !isset($item->url)) {
            throw new Exception("There is no route name or url defined for the '$item->name' menu item");
        }

        if (isset($item->route_name)) {

            return route($item->route_name); // http://naykel.site/naykel/docs

        } elseif (isset($item->url)) {

            return url($item->url);     // http://naykel.site/about

        } else {

            throw new Exception($item->name . " menu item has a problem. It's likely missing the route in wep.php!");
        }
    }
}
