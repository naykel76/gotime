<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{

    public function __construct(
        public $layout = false,
    ) {
    }

    public function render()
    {
        return $this->layout
            ? view('gotime::components.sidebar.' . $this->layout)
            : view('gotime::components.sidebar');
    }
}
