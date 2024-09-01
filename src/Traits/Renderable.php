<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Support\Str;

trait Renderable
{
    /**
     * Renders the view for the current component.
     *
     * @return \Illuminate\View\View The view instance.
     */
    public function render(): \Illuminate\View\View
    {
        if (method_exists($this, 'prepareData')) {
            $data = $this->prepareData();
        }

        // dd($this->pageTitle ?? $this->getPageTitle());
        return view($this->view, $data ?? [])
            ->layout(\Naykel\Gotime\View\Layouts\AppLayout::class, [
                'pageTitle' => $this->pageTitle ?? $this->getPageTitle(),
                'layout' => $this->layout ?? 'admin'
            ]);
    }


    /**
     * Sets the page title based on the current route.
     *
     * @return string The page title.
     */
    private function setPageTitle(): void
    {
        // $action = $this->editingModelExists() ? 'Edit ' : 'Create ';
        // $lastSegment = dotLastSegment($this->routePrefix);
        // $exclude = ['media']; // prevent singular conversion (media->medium)

        // if (in_array($lastSegment, $exclude)) {
        //     $this->title = $action . Str::title($lastSegment);
        //     return;
        // }

        // $this->title = $action . Str::singular(Str::title($lastSegment));
    }
}
