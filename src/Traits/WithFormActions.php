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
        $this->resetForm(); // Ensure form is reset before creating a new model

        $model = method_exists($this->form, 'createNewModel')
            ? $this->form->createNewModel($this->initialData ?? [])
            : $this->modelClass::make($this->initialData ?? []);

        $this->form->init($model);

        $this->showModal = true;
    }

    /**
     * Save the current form data and handle post-save actions.
     *
     * This method validates and persists the model, handles notifications,
     * dispatches events, and manages redirection based on the action parameter.
     * The form is reset after save unless using save_stay action.
     *
     * @param  string|null  $action  The post-save action (save_close, save_new, save_stay, save_edit)
     */
    public function save(?string $action = null): void
    {
        // Capture whether model is new before saving (once saved, it will have an ID)
        $isNewModel = $this->isNewModel();

        // Persist the model via the Crudable trait
        $model = $this->form->save();

        // For existing models with save_edit action, notify and return early
        // This prevents unnecessary redirects on subsequent saves
        if (! $isNewModel && $action == 'save_edit') {
            $this->dispatch('notify', 'Saved successfully!');

            return;
        }

        // Handle redirects for route-based workflows
        if ($action) {
            $this->handleRedirect($this->routePrefix, $action, $model);
        }

        $this->dispatch('notify', 'Saved successfully!');
        $this->dispatch('model-saved');

        // Reset form for all actions except save_stay
        // This allows route-based forms to persist data when staying on the same page
        if ($action !== 'save_stay') {
            $this->resetForm();
        }
    }

    /**
     * Handles redirection based on the provided action.
     *
     * Laravel's route model binding automatically uses the model's route key
     * (e.g., slug, uuid, or id) based on the route definition.
     *
     * @param  string  $routePrefix  The prefix for the route
     * @param  string  $action  The action to perform (save_close, save_new, save_stay, save_edit)
     * @param  mixed  $model  The model instance for generating route parameters
     *
     * @throws \Exception If an invalid action is provided
     */
    private function handleRedirect(string $routePrefix, string $action, $model = null)
    {
        return match ($action) {
            'save_close', 'delete_close' => redirect(route("$this->routePrefix.index")),
            'save_new' => redirect(route("$routePrefix.create")),
            'save_edit' => redirect(route("$routePrefix.edit", $model)),
            'save_stay' => null, // Stay on current page without redirect
            default => throw new \Exception("Invalid action: $action"),
        };
    }

    /**
     * Delete a model instance from the database and and optionally handle redirection.
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
     * Cancels the current form action and resets all form state.
     *
     * This method resets the form to a clean state and closes the modal.
     * The form will be properly initialized when create/edit methods are called.
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->dispatch('close-modal');
    }

    /**
     * Resets the form and UI state to a completely fresh state.
     *
     * This method resets form data, UI properties (modal visibility, selected ID),
     * and clears error bags to ensure a clean state for the next operation.
     */
    public function resetForm(): void
    {
        $this->form->reset();
        $this->reset(['showModal', 'selectedId']);
        $this->resetErrorBag();
    }
}
