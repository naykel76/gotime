<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;
use Naykel\Gotime\DTO\MenuDTO;

class Menu extends Component
{
    /**
     * The JSON file containing the menu data.
     */
    public object $file;

    public function __construct(
        public string $filename = 'nav-main',   // name of json file in navs directory
        public string $menuname = 'main',       // specific menu from json file
        public string $layout = 'collapse',     // collapse|dropdown
        // public string $trigger = 'click',       // click|hover|none
        public string $menuTitle = '',
        public string $itemClass = '',
        public bool $withIcons = false,
        public bool $newWindow = false,
        public string $iconClass = '',
        public bool $open = false,              // default state for collapse menu
    ) {
        $this->file = getJsonFile(resource_path("navs/$this->filename.json"));
    }

    /**
     * Retrieves a menu from the nav.json file by its name and returns it as a MenuDTO.
     *
     * @param  string  $menuName  The name of the menu to retrieve.
     * @return MenuDTO The requested menu, wrapped in a MenuDTO.
     *
     * @throws InvalidArgumentException If the requested menu does not exist in the file.
     */
    public function getMenu(string $menuName): MenuDTO
    {
        if (! isset($this->file->$menuName)) {
            throw new \InvalidArgumentException("There is no menu object named '$menuName' found in the `$this->filename` json file.");
        }

        return new MenuDTO(collect($this->file->$menuName), $menuName);
    }

    /**
     * Check if the current URL matches the given URL.
     *
     * Note: Sanitizing the URL is not necessary as it is handled in the RouteDTO.
     *
     * @param  string  $url  The URL to compare with the current request URL.
     * @return bool True if the URLs match, false otherwise.
     */
    public function isActive(string $url): bool
    {
        return request()->is($url);
    }

    public function render()
    {
        $viewPath = view()->exists("components.layouts.menu.$this->layout")
            ? "components.layouts.menu.$this->layout"
            : "gotime::components.v2.menu.$this->layout";

        return view($viewPath)
            ->with([
                'menuItems' => $this->getMenu($this->menuname)->menuItems,
            ]);
    }
}
