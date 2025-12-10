<?php

namespace Naykel\Gotime\Traits;

use Livewire\Attributes\On;

trait WithFormActions
{
    // ////////////////////////////////////////////////////////////////
    //     This trait is tightly coupled to the Crudable trait      //
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

    /**
     * Create a new model instance.
     *
     * Creates a new model instance and initialises the form with it. If the form
     * has a custom createNewModel() method, it will be used. Otherwise, a new
     * instance is created using $modelClass.
     */
    #[On('create-model')]
    public function create(): void
    {
        $this->resetFormData();

        $model = method_exists($this->form, 'createNewModel')
            ? $this->form->createNewModel($this->initialData ?? [])
            : $this->modelClass::make($this->initialData ?? []);

        $this->form->init($model);

        $this->showModal = true;
    }

    /**
     * Save the current form data and handle post-save actions.
     *
     * Delegates to the form object's save() method to persist the model.
     * Handles notifications, event dispatching, and optional redirects for
     * route-based workflows.
     *
     * @param  string|null  $action  Optional post-save action (save_close, save_new, save_edit)
     */
    public function save(?string $action = null): void
    {

        $isNewModel = $this->isNewModel();

        // Persist the model via the form object's save() method
        // Save the model using the form's save() method
        $model = $this->form->save();

        // If editing an existing model and the action is 'save_edit', notify and exit early
        // This avoids unnecessary redirects or additional processing on repeated saves
        if (! $isNewModel && $action === 'save_edit') {
            $this->dispatch('notify', 'Saved successfully!');

            return;
        }

        // Handle redirects for route-based workflows
        if ($action && $action !== 'skip_redirect') {
            $this->handleRedirect($this->routePrefix, $action, $model);
        }

        $this->dispatch('notify', 'Saved successfully!');
        $this->dispatch('model-saved');

        // Reset and close modal only when no action provided
        if (! $action) {
            $this->closeModal();
        }
    }

    /**
     * Handles redirection based on the provided action.
     *
     * Used for route-based workflows. Laravel's route model binding automatically
     * uses the model's route key (slug, uuid, or id) based on route definition.
     */
    private function handleRedirect(string $routePrefix, string $action, $model)
    {
        return match ($action) {
            'save_close' => redirect(route("$routePrefix.index")),
            'save_new' => redirect(route("$routePrefix.create")),
            'save_edit' => redirect(route("$routePrefix.edit", $model)),
            default => throw new \Exception("Invalid action: $action"),
        };
    }

    /**
     * Delete a model instance from the database.
     */
    #[On('delete-model')]
    public function delete(?int $id = null): void
    {
        $this->modelClass::findOrFail($id)->delete();
        $this->dispatch('model-deleted');
        $this->dispatch('notify', 'Deleted successfully!');
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
     * Cancels the current form action and closes the modal.
     *
     * This method closes the modal dialog. The form state remains unchanged
     * and will be properly initialized when create/edit methods are called again.
     */
    public function cancel(): void
    {
        $this->resetFormData(); // Not strictly necessary but handles edge cases cleanly
        $this->closeModal();
    }

    /**
     * Closes the modal by resetting modal-related state.
     */
    private function closeModal(): void
    {
        $this->reset(['showModal', 'selectedId']);
    }

    /**
     * Resets only the form data and errors, without affecting modal state.
     */
    private function resetFormData(): void
    {
        $this->form->reset();
        $this->resetErrorBag();
    }

    public function imageUrl()
    {
        if ($this->form->tmpUpload) {
            return $this->form->tmpUpload->temporaryUrl();
        }

        // editing model exists
        if (isset($this->form->editing)) {
            return $this->form->editing->featuredImageUrl();
        }

        return url('/svg/placeholder.svg');
    }
}
