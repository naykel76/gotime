<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

trait Crudable
{

    public string $pageTitle = '';

    /**
     * Finds the model instance with the given id and set it to the `form`
     * property. The method listens for the 'edit-model' event generally
     * dispatched from the `Table` class.
     *
     * @param int $id The id of the model instance to find
     * @return void
     */
    #[On('edit-model')]
    public function edit(int $id): void
    {
        $this->resetErrorBag(); // clear any previous error messages
        $this->form->setModel($this->model::findOrFail($id));
    }

    /**
     * Create a new record in the database.
     *
     * @param Model $model The model instance
     * @param array $validated The validated data to be stored
     * @return void
     */
    public function store(Model $model, array $validated): void
    {
        $model::create($validated);
    }

    /**
     * Update an existing record in the database.
     *
     * @param Model $model The model instance
     * @param array $validated The validated data to be updated
     * @return void
     */
    public function update(Model $model, array $validated): void
    {
        $model->update($validated);
    }

    public function save(): void
    {
        $validated = $this->form->validate();
        $model = $this->form->getModel();

        // If the model exists, call the update method; otherwise, call the store method
        $model->exists
            ? $this->update($model, $validated)
            : $this->store($model, $validated);

        $this->dispatch('notify', ('Saved!'));
        $this->dispatch('saved');

        // reset the form with a new model instance
        // $this->form->setModel($this->initModel());
    }

    // ======================================================================
    // HELPER METHODS
    // ======================================================================
    //
    //

    /**
     * Checks if the model exists. If it does, the form will be in 'edit'
     * mode. Otherwise, it will be in 'create' mode.
     */
    private function modelExists(): bool
    {
        return $this->form->getModel()->exists;
    }

    /**
     * Sets the page title based on the route prefix.
     *
     * If a model exists, the title will be 'Edit {ModelName}', otherwise it
     * will be 'Create {ModelName}'. The model name is derived from the last
     * segment of the route prefix, converted to title case and singular form.
     *
     * @param string $routePrefix The route prefix to derive the model name from.
     * @return string The page title.
     */
    private function setPageTitle(string $routePrefix): string
    {
        $action = $this->modelExists() ? 'Edit ' : 'Create ';
        return "{$action}" . Str::singular(Str::title(dotLastSegment($routePrefix)));
    }
}
