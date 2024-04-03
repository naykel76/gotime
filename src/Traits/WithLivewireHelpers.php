<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Support\Str;

trait WithLivewireHelpers
{
    /**
     * The title of the page.
     *
     * @var string
     */
    public string $pageTitle = '';

    /**
     * @var bool|int Represents the ID of the item to be actioned.
     */
    public $actionId = false;

    /**
     * ----------------------------------------------------------------------
     * INTERMEDIARY METHODS
     * ----------------------------------------------------------------------
     * These methods are intended to create a bridge between the FormObject and
     * the Component class. This allows blade components to directly communicate
     * with the FormObjects methods.
     *
     */

    public function create(): void
    {
        $this->form->create();
    }

    public function edit($id): void
    {
        $this->form->edit($id);
    }

    /**
     * Delete a model instance from the database and and optionally handle redirection.
     *
     * This delete method is tightly coupled to the FormObjects model instance.
     *
     * @return void
     */
    // NK?? I am conflicted about this method. I think is makes more sense to
    // include it in the `formable` trait, however there will be situations
    // where the form is not used and the delete method is required.
    public function delete(string $id = null): void
    {
        if (!isset($this->model)) {
            throw new \Exception('Property $model is not set in ' . __CLASS__ . ". ---- Eg. protected \$model = User::class;");
        }

        $this->model::find($this->actionId)->delete();

        $this->reset('actionId');
        $this->dispatch('item-deleted');
    }

    public function save(string $action = null): void
    {
        $model = $this->form->save();

        if ($action == 'save_edit') {
            handleRedirect($this->routePrefix, $action, $model->id);
        }

        $this->dispatch('notify', 'Saved successfully!');
        $this->dispatch('pondReset');
    }

    public function saveOrNew(): void
    {
        $this->form->saveOrNew();
        $this->dispatch('item-saved');
        $this->dispatch('notify', 'Updated successfully!');
    }

    /**
     * Resets the form and related state.
     *
     * This method, acting as an alias for `resetForm`, provides a more intuitive
     * name in certain contexts.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->form->resetForm();
    }

    public function setActionId($id): void
    {
        $this->actionId = $id;
    }

    /**
     * ----------------------------------------------------------------------
     * RENDER AND LAYOUT METHODS
     * ----------------------------------------------------------------------
     *
     *
     */

    /**
     * Renders the view for the current component.
     *
     * @return \Illuminate\View\View The view instance.
     */
    public function render()
    {

        return view($this->view)
            ->layout(\Naykel\Gotime\View\Layouts\AppLayout::class, [
                'pageTitle' => $this->title ?? $this->pageTitle ?? 'Admin',
                'layout' => $this->layout ?? 'admin'
            ]);
    }

    /**
     * This method checks if the if the form is set, and the form's editing
     * model exists. If the form or the model does not exist, the method will
     * return false. Otherwise, it will return true.
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

    /**
     * Sets the page title based on the current route.
     *
     * @return string The page title.
     */
    private function setPageTitle(): void
    {
        $action = $this->editingModelExists() ? 'Edit ' : 'Create ';
        $lastSegment = dotLastSegment($this->routePrefix);
        $exclude = ['media']; // prevent singular conversion (media->medium)

        if (in_array($lastSegment, $exclude)) {
            $this->title = $action . Str::title($lastSegment);
            return;
        }

        $this->title = $action . Str::singular(Str::title($lastSegment));
    }
}
