<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
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
     * Save the current form data.
     *
     * Delegates to the form object's save() method to persist the model,
     * dispatches notifications and events.
     *
     * @return Model The model that was persisted
     */
    public function save(): Model
    {
        $model = $this->form->save();
        $this->dispatch('notify', 'Saved successfully!');
        $this->dispatch('model-saved');

        return $model;
    }

    /**
     * Save and close the modal.
     */
    public function saveAndClose(): void
    {
        $this->save();
        $this->closeModal();
    }

    /**
     * Save and redirect to the create route.
     *
     * Requires $routePrefix to be set.
     */
    public function saveAndNew(): mixed
    {
        $this->save();

        return $this->handleRedirect('save_new');
    }

    /**
     * Save and redirect to the edit route.
     *
     * For existing models, this notifies without redirecting.
     * Requires $routePrefix to be set.
     */
    public function saveAndEdit(): mixed
    {
        // Capture before save() so the ID isn't set yet on new models
        $isNew = $this->isNewModel();
        $model = $this->save();

        if (! $isNew) {
            return null;
        }

        return $this->handleRedirect('save_edit', $model);
    }

    /**
     * Handles redirection based on the provided action.
     *
     * Used for route-based workflows. Laravel's route model binding automatically
     * uses the model's route key (slug, uuid, or id) based on route definition.
     */
    private function handleRedirect(string $action, ?Model $model = null): mixed
    {
        return match ($action) {
            'save_close' => $this->redirectRoute("{$this->routePrefix}.index"),
            'save_new' => $this->redirectRoute("{$this->routePrefix}.create"),
            'save_edit' => $this->redirectRoute("{$this->routePrefix}.edit", $model),
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
    protected function isNewModel(): bool
    {
        if (! isset($this->form) || ! isset($this->form->editing)) {
            return true;
        }

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


    protected function titlePrefix(): string
    {
        return $this->isNewModel() ? 'Create' : 'Edit';
    }
}
