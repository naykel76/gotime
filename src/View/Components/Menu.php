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
        public string $iconClass = '',
        public bool $isNewMenuComponent = false,       // tmp variable
    ) {
        $this->file = getJsonFile(resource_path("navs/$this->filename.json"));
    }

    public function render()
    {
        if ($this->isNewMenuComponent) {
            // used for single menu
            return view('gotime::components.menu-new')->with(['menu' => $this->getMenu($this->menuname)]);
        }

        return $this->menuname
            ?  view('gotime::components.menu')->with(['menu' => $this->getMenu($this->menuname)])
            : view('gotime::components.menus')->with(['file' => $this->file]);
    }

    /**
     * Check if the current route is active
     */
    public function isActive($item)
    {

        $x = '';
        if (isset($item->url)) {
            if (request()->is($item->url)) {
                $x = 'danger';
            } else {
                $x = 'orange';
            };
        }

        // dd($item);
        // dd('here');
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
     * Generates relative url from menu item (object)
     */
    public function getUrl(object $item): string
    {
        if (!isset($item->route_name) && !isset($item->url)) {
            throw new Exception("There is no route name or url defined for the '$item->name' menu item");
        }

        if (isset($item->route_name)) {
            return route($item->route_name, absolute: false);
        } else {
            return ltrim($item->url, '/'); // strip '/' to prevent errors
        }

        throw new Exception($item->name . " menu item has a problem. It's likely missing the route in wep.php!");
    }
}
