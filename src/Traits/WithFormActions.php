<?php

namespace Naykel\Gotime\Traits;

trait WithFormActions
{
    //////////////////////////////////////////////////////////////////
    //     This trait is tightly coupled to the Formable trait      //
    //      the editing property must be $this->form->editing       //
    //////////////////////////////////////////////////////////////////

    /**
     * Flag to show or hide modal
     */
    public bool $showModal = false;

    /**
     * The ID of the selected item and flag to show or hide modal.
     */
    public bool|int $selectedItemId = false;

    /**
     * Edit the specified model by its ID.
     *
     * This method finds a model by its ID, initializes the form with the model,
     * and shows the modal dialog.
     *
     * @param  int  $id  The ID of the model to edit.
     */
    public function edit($id): void
    {
        $model = $this->modelClass::findOrFail($id);
        $this->form->init($model);
        $this->showModal = true;
    }

    public function create(): void
    {
        if (! method_exists($this->form::class, 'createNewModel')) {
            throw new \Exception('The createNewModel method is not defined in the class: ' . $this->form::class);
        }

        $model = $this->form->createNewModel($this->initialData ?? []);
        $this->form->init($model);
        $this->showModal = true;
    }

    public function save(?string $action = null): void
    {
        // this must happen before the form is saved, otherwise there will be an
        // `id` and the model will not be new
        $isNewModel = $this->isNewModel();

        // call the save method from the formable trait and persist the model
        $model = $this->form->save();

        // this only needs to redirect on the first save
        if (! $isNewModel && $action == 'save_edit') {
            $this->dispatch('notify', 'Saved successfully!');

            return;
        }

        if ($action) {
            $this->handleRedirect($this->routePrefix, $action, $model->id);
        }

        $this->dispatch('notify', 'Saved successfully!');

        $this->showModal = false;
    }

    /**
     * Delete a model instance from the database and and optionally handle redirection.
     */
    public function delete(?int $id = null): void
    {
        $this->modelClass::findOrFail($id)->delete();
        $this->reset('selectedItemId');
        // $this->dispatch('item-deleted');
    }

    /**
     * Clear the forms temporary file upload.
     */
    public function clearTmpUpload(): void
    {
        $this->form->tmpUpload = null;
    }

    /**
     * Determines if the model being edited is new (i.e., not yet saved in the database).
     *
     * @return bool Returns true if the model is new (without an ID), false otherwise.
     */
    private function isNewModel(): bool
    {
        return is_null($this->form->editing->id);
    }

    /**
     * Handles redirection based on the provided action.
     *
     * @param  string  $routePrefix  The prefix for the route.
     * @param  string  $action  The action to be performed 'save_close', 'delete_close' ...
     * @param  int  $id  The optional ID for routes that require it.
     *
     * @throws \Exception Throws an exception if an invalid action is provided.
     */
    private function handleRedirect(string $routePrefix, string $action, ?int $id = null)
    {

        return match ($action) {
            'save_close', 'delete_close' => redirect(route("$this->routePrefix.index")),
            'save_new' => redirect(route("$routePrefix.create")),
            'save_edit', 'save_stay' => redirect(route("$routePrefix.edit", $id)),
            default => throw new \Exception("Invalid action: $action"),
        };
    }

    /**
     * Cancel the form action and close the modal dialog.
     */
    public function cancel(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }
}
