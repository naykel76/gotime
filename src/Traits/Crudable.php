<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Naykel\Gotime\Facades\FileManagement;

trait Crudable
{
    use WithFileUploads;

    public string $pageTitle = '';

    /**
     * The validated array of the form instance.
     *
     * Gives more control over the validated data before it gets saved.
     *
     * @var array
     */
    public array $validated = [];

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
        $this->validated = $this->form->validate();

        $model = $this->form->getModel();

        $this->beforePersistHook();

        $this->handleFile($this->form->tmpUpload, $this->validated, 'tmpUpload');

        $model->exists
            ? $this->update($model, $this->validated)
            : $this->store($model, $this->validated);

        $this->afterPersistHook();

        $this->dispatch('notify', ('Saved!'));
        $this->dispatch('saved');
    }

    /*
    |--------------------------------------------------------------------------
    | FILE UPLOADS
    |--------------------------------------------------------------------------
    |
    */

    private function handleFile(?UploadedFile $file, array &$validated, string $dbColumnName)
    {
        // Purge null values from the validated array. This is to avoid overwriting
        // existing data with null values during the model update process.

        // Since the validated array is passed by reference, it's crucial to verify
        // the existence of a key in the array before accessing it. This prevents
        // potential "undefined array key" errors.
        if (array_key_exists('tmpUpload', $validated) && $validated['tmpUpload'] === null) {
            unset($validated['tmpUpload']);
            return;
        }

        if ($file) {
            /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
            $fileInfo = FileManagement::saveWithUnique($file, $this->storage['path'], $this->storage['disk']);
            $validated[$dbColumnName] = $fileInfo->path();
            unset($validated['tmpUpload']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HOOKS
    |--------------------------------------------------------------------------
    |
    */


    /**
     * This method is a hook that gets called after validation and before
     * persisting data. It can be used to manipulate the validated data before
     * it gets saved. For example, you might want to add, remove or change
     * some data.
     *
     * Note: The $validated data is passed by reference, so any changes here
     * will affect the original data.
     *
     * @param array $validated Data passed by reference
     * @return void
     */
    protected function beforePersistHook(array &$validated): void
    {
        // This is where you can perform additional tasks and manipulate the
        // $validated data. Since the data is passed by reference
        // (&$validated), any changes here will affect the original data.
    }



    /**
     * Perform additional tasks after persisting the database
     */
    protected function afterPersistHook(): void
    {
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    |
    |
    */

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
        return $action . Str::singular(Str::title(dotLastSegment($routePrefix)));
    }
}
