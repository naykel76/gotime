<?php

namespace Naykel\Gotime\Traits;

use Illuminate\View\View;
use Livewire\Mechanisms\ComponentRegistry;
use Naykel\Gotime\View\Layouts\AppLayout;

trait Renderable
{
    /**
     * Renders the view for the current component.
     *
     * This method handles rendering the view for the component, optionally
     * passing any data from `prepareData()` if it exists. It uses Livewire's
     * `ComponentRegistry` to resolve the view name.
     *
     * @return View The rendered view instance.
     */
    public function render(): View
    {
        $data = method_exists($this, 'prepareData') ? $this->prepareData() : [];

        return view($this->getView(), $data)
            ->layout(AppLayout::class, [
                'pageTitle' => $this->pageTitle ?? null,
                'layout' => $this->layout ?? config('gotime.livewire_layout'),
            ]);
    }

    /**
     * Get the view name to render.
     *
     * If a custom view is specified via the `$view` property, it will be used.
     * Otherwise, the view name will be generated based on the class name in
     * snake case, or resolved through the Livewire ComponentRegistry.
     *
     * @return string The view name to render.
     */
    protected function getView(): string
    {
        return $this->view ?? $this->getViewFromRegistry();
    }

    /**
     * Resolves the view name using the Livewire ComponentRegistry class.
     *
     * This method uses the `ComponentRegistry` to map the current component's
     * class to a corresponding view name. It provides an abstraction over the
     * standard Livewire view name generation process.
     *
     * @return string The resolved view name.
     */
    protected function getViewFromRegistry(): string
    {
        $componentRegistry = app(ComponentRegistry::class);
        $componentName = $componentRegistry->getName(static::class);

        return 'livewire.' . $componentName;
    }
}
