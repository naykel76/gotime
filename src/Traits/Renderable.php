<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Support\Str;

trait Renderable
{
    public string $pageTitle;

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
                'pageTitle' => $this->getPageTitle(),
                'apples' => 'sdf',
                'layout' => $this->layout ?? 'admin'
            ]);
    }

    /**
     * Initialise or get the page title for the current action.
     * 
     * @param string $action 
     * @return string 
     */
    private function getPageTitle($action = ''): string
    {
        if (!isset($this->pageTitle)) {
            $this->setPageTitle($action);
        }
        return $this->pageTitle;
    }

    /**
     * Get the page title for the current action.
     * 
     * @param string $action 
     * @return string 
     */
    private function setPageTitle($action = ''): void
    {
        // $action = $this->editingModelExists() ? 'Edit ' : 'Create ';
        $lastSegment = dotLastSegment($this->routePrefix);
        $title = Str::title($lastSegment);

        $exclude = ['media']; // prevent singular conversion (media->medium)
        $pageTitle = in_array($lastSegment, $exclude) ? $title : Str::singular($title);

        $this->pageTitle = $action . $pageTitle;
    }
}
