<?php

namespace Naykel\Gotime\Traits;

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

        return view($this->view, $data ?? [])
            ->layout(\Naykel\Gotime\View\Layouts\AppLayout::class, [
                'pageTitle' => $this->pageTitle ?? $this->getPageTitle(),
                'layout' => $this->layout ?? config('naykel.livewire_layout'), // default `app`
            ]);
    }
}
