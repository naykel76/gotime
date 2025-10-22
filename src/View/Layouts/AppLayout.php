<?php

namespace Naykel\Gotime\View\Layouts;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public function __construct(
        public $title = null,
        public $layout = null,
        public bool $hasContainer = false,
        public bool $hasTitle = false,
    ) {}

    public function render()
    {
        if (! $this->layout || $this->layout == '') {
            $this->layout = config('gotime.template');
        }

        if (view()->exists("components.layouts.$this->layout")) {
            return view("components.layouts.$this->layout");
        }

        return view("gotime::components.layouts.$this->layout");
    }
}
