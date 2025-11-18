<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;
use Naykel\Gotime\DTO\NavDTO;

class Nav extends Component
{
    /**
     * The JSON file containing the navigation data.
     */
    public object $file;

    public function __construct(
        public string $filename = 'nav-main',   // name of json file in navs directory
        public string $menuname = 'main',       // specific menu from json file
        public string $layout = 'collapse',     // collapse|dropdown
        public bool $open = false,              // default state child menus
        public string $navClass = '',           // classes for <nav> element
        public bool $withIcons = false,
        public string $iconClass = '',
        public string $menuTitle = '',
    ) {
        $this->file = getJsonFile(resource_path("navs/$this->filename.json"));
    }

    /**
     * Retrieves a navigation menu from the nav.json file by its name and returns it as a NavDTO.
     *
     * @param  string  $menuName  The name of the menu to retrieve.
     * @return NavDTO The requested menu, wrapped in a NavDTO.
     *
     * @throws InvalidArgumentException If the requested menu does not exist in the file.
     */
    public function getMenu(string $menuName): NavDTO
    {
        if (! isset($this->file->$menuName)) {
            throw new \InvalidArgumentException("There is no menu object named '$menuName' found in the `$this->filename` json file.");
        }

        return new NavDTO(collect($this->file->$menuName), $menuName);
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
        $url = ltrim($url, '/');

        return request()->is($url);
    }

    public function render()
    {
        $viewPath = view()->exists("components.$this->layout")
            ? "components.$this->layout"
            : "gotime::components.nav.$this->layout";

        return view($viewPath)
            ->with([
                'menuItems' => $this->getMenu($this->menuname)->menuItems,
            ]);
    }
}
