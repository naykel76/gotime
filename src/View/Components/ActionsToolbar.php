<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;

class ActionsToolbar extends Component
{
    public $formName;       // name/id of form to be submitted
    public bool $preview;   // hide show preview button
    public $routeName;      // name of the current resource

    /**
     * Create a new component instance.
     */
    public function __construct($formName, $preview = true, $routeName = null)
    {
        $this->formName = $formName;
        $this->preview = $preview;
        $this->routeName = $routeName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('gotime::components.toolbars.actions-toolbar');
    }
}
