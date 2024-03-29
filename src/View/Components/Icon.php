<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class Icon extends Component
{

    public array $icons = [
        'arrow-down-tray' => ['download'],
        'arrow-left-circle' => ['previous', 'back'],
        'arrow-path' => ['refresh'],
        'arrow-right-circle' => ['next'],
        'arrow-right-end-on-rectangle' => ['enter'],
        'arrow-right-start-on-rectangle' => ['exit'],
        'arrow-up-tray' => ['upload'],
        'magnifying-glass' => ['search'],
        'x-mark' => ['close', 'cross']
    ];

    public function __construct(public string $name, public ?string $type = null)
    {
        $this->type = $type ?? config('naykel.component.icon.type');
    }

    /**
     * Determine if the icon name is an alias, and if it is, return the
     * corresponding key name (filename) for the icon.
     */
    function getIconKeyFromAlias($iconName): string
    {
        foreach ($this->icons as $key => $aliases) {
            if (in_array($iconName, $aliases)) {
                return $key;
            }
        }

        return $iconName;
    }

    public function render()
    {

        $name = $this->getIconKeyFromAlias($this->name);

        $viewPath = "gotime::components.v2.icon.{$this->type}.{$name}";

        if (!View::exists($viewPath)) {
            throw new \InvalidArgumentException("Icon '$name' view does not exist at path: {$viewPath}");
        }

        return view($viewPath);
    }
}
