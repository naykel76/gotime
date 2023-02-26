<?php

namespace Naykel\Gotime\View\Components;

use Exception;
use Illuminate\View\Component;

class MenuNew extends Component
{

    /**
     * nav.json file
     */
    public object $file;

    public function __construct(
        public string $filename = "nav-main",   // name of json file in navs directory
        public string $menuname = 'main',       // specific menu from json file
        public string $layout = 'click',        // click|hover|none
    ) {
        $this->file = getJsonFile(resource_path("navs/$this->filename.json"));
    }

    public function render()
    {
        return view("gotime::components.menu-child-$this->layout")->with(['menu' => $this->getMenu($this->menuname)]);
    }

    /**
     * Get the specified menu from the nav object
     */
    public function getMenu($menuname): object
    {

        if (empty($this->file->$menuname)) {
            throw new Exception("There is no menu object named '$menuname' found in the `$this->filename` json file.");
        }

        return $this->file->$menuname;
    }

    /**
     * Generates relative url from menu objects route_name or url
     */
    public function getUrl(object $item): string|null
    {

        // if the menu item has children, AND there is no `route_name` or
        // `url` you are safe to assume this is a parent item with a hover or
        // clickable so do not attempt to create a link, just exit.
        if ($this->isParent($item) && !isset($item->route_name) && !isset($item->url)) return '#';

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

    /**
     * Check if the menu item has any children
     */
    public function isParent($item): bool
    {
        return property_exists($item, 'children');
    }

    /**
     * Check if the current url is active.
     *
     * The link will always come through as the url because it is run through
     * the getUrl method before it gets here.
     */
    public function isActive(string $url): bool
    {
        // strip the leading forward slash from the $url parameter because
        // request()->is() expects the URL path without the leading slash.
        // This creates a problem for the home page but we can handle it by
        // checking if the URL is empty or a single forward slash.
        $url = ltrim($url, '/');

        if ($url === '' || $url === '/') {
            return request()->path() === '' || request()->path() === '/';
        }

        return request()->is($url);
    }
}
