<?php

namespace Naykel\GoTime\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
    public $filename; // name of json file in navs directory
    public $dir = "../resources/views/navs/"; // json file storage directory
    public $menuname; // name of menu in json file

    public function __construct($filename = "nav-main", $menuname)
    {
        $this->filename = $filename;
        $this->menuname = $menuname;
    }

    public function render()
    {

        $file = $this->dir . $this->filename . '.json';

        // check file exists
        if (!file_exists($file)) {
            dd($file . ' not found');
            return;
        }

        // fetch json file and decode
        $menu = file_get_contents($file);
        $menu = json_decode($menu, false); // false = object, true = accArray

        return view('gotime::components.menu')->with(['menu' => $menu]);
    }
}
