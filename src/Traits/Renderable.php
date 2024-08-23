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
}
