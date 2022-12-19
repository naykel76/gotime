<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
    public function __construct(
        public string $menuname,                // name of json file in navs directory
        public string $filename = "nav-main",   // menu name in json file
        public bool $useIcons = false,          // depreciated for iconit
        public bool $useIconit = false,         // iconit icon name
        public string $itemClass = '',
    ) {
    }

    public function render()
    {
        $file = getJsonFile(resource_path("navs/$this->filename.json"));

        return view('gotime::components.menu')->with(['menu' => $file]);
    }
}
