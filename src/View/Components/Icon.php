<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class Icon extends Component
{
    public function __construct(public string $name, public ?string $style = null)
    {
        $this->style = $style ?? config('naykel.component.icon.style');
    }

    public function render()
    {
        $viewPath = "gotime::components.v2.icon.{$this->style}.{$this->name}";

        if (!View::exists($viewPath)) {
            throw new \InvalidArgumentException("Icon '$this->name' view does not exist at path: {$viewPath}");
        }

        return view($viewPath);
    }
}
