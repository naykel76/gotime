<?php

namespace Naykel\Gotime\View\Layouts;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public function __construct(
        public $pageTitle = null,
        public $layout = null,

        public bool $hasContainer = false,  // specify if main element has container
        public bool $hasTitle = false,      // specify if main element has h1 title
        public string $mainClasses = '',    // classes for <main>
    ) {
    }

    public function render()
    {
        // fall back to default if null or empty
        if (!$this->layout || $this->layout == '') {
            $this->layout = config('naykel.template');
        }

        if (view()->exists("layouts.$this->layout")) {
            return view("layouts.$this->layout");
        }

        return view("gotime::layouts.$this->layout");
    }
}
