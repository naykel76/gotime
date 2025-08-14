<?php

namespace Naykel\Gotime\Traits;

use Livewire\Attributes\On;

trait WithFormActions
{
    // ////////////////////////////////////////////////////////////////
    //     This trait is tightly coupled to the Formable trait      //
    //      the editing property must be $this->form->editing       //
    // ////////////////////////////////////////////////////////////////

    /**
     * Flag to show or hide modal
     */
    public bool $showModal = false;

    /**
     * The ID of the selected item.
     *
     * Used to track which item is currently selected for actions like edit or
     * delete and control modal visibility.
     */
    public ?int $selectedId = null;

    /**
     * Edit the specified model by its ID.
     *
     * This method finds a model by its ID, initializes the form with the model,
     * and shows the modal dialog.
     *
     * @param  int  $id  The ID of the model to edit.
     */
    #[On('edit-model')]
    public function edit($id): void
    {
        $model = $this->modelClass::findOrFail($id);
        $this->form->init($model);
        $this->showModal = true;
    }

    #[On('create-model')]
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
        $this->dispatch('model-saved');
        $this->showModal = false;
    }

    /**
     * Delete a model instance from the database and and optionally handle redirection.
     */
    #[On('delete-model')]
    public function delete(?int $id = null): void
    {
        $this->modelClass::findOrFail($id)->delete();
        $this->dispatch('model-deleted');
        $this->reset('selectedId');
    }

    /**
     * Clear the forms temporary file upload. Required for filepond to work
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
     * Cancels the current form action, resets UI state and closes modal.
     *
     * Note: We don't reset the form object here to avoid errors in nested
     * components. The form will be properly initialized when create/edit
     * methods are called.
     */
    public function cancel(): void
    {
        // dont reset the form or it will throw errors in nested components.
        // Rely on the initialisation of the form to reset the form state.
        $this->reset(['showModal', 'selectedId']);
        $this->resetErrorBag();
        $this->dispatch('close-modal');
    }

    /**
     * Resets the form to a completely fresh state by creating a new instance.
     * All properties are reset to their default values and error bags are cleared.
     */
    public function resetForm(): void
    {
        $formClass = get_class($this->form);
        $this->form = new $formClass($this, 'form');
        $this->resetErrorBag();
    }
}
