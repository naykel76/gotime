<?php

namespace Naykel\Gotime\Traits;

trait WithLivewireHelpers
{
    use Renderable;
    // use Formable;
    // use Actionable;
    // use UtilityTrait;

    /**
     * This method checks if the form is set, and the form's editing model exists. 
     *
     * @return bool True if the form's editing model exists, false otherwise.
     */
    private function editingModelExists(): bool
    {
        if (isset($this->form)) {
            $editingModel = $this->form->getModel();
            return $editingModel ? $editingModel->exists : false;
        }
        return false;
    }
}
