<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;

class Parsedown extends Component
{

    public $file_path; // fully qualified file path

    /**
     *
     * @param string $dir root directory inside resources folder
     * @param string $path subdirectory and filename excluding .md
     * @param string $fullPath including filename excluding .md
     * @return void
     */

    public function __construct(
        public string $dir = 'markdown/', // default
        public string $path = '',
        public string $fullPath = '',
    ) {

        $this->dir = resource_path($this->dir);

        if (!empty($fullPath)) {
            $this->file_path = $this->fullPath . ".md";
        } else {
            $this->file_path = $this->dir . $path . ".md";
        }
    }

    public function render()
    {
        if (!file_exists($this->file_path)) {
            dd($this->file_path . ' not found');
            return;
        }

        $file = file_get_contents($this->file_path);

        return view('gotime::components.parsedown')->with(['file' => $file]);
    }
}
