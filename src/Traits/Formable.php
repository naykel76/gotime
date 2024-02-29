<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Naykel\Gotime\Facades\FileManagement;

trait Formable
{

    /**
     * @var Model The form object model currently being edited.
     */
    public ?Model $editing;

    /**
     * @var bool A flag used to toggle various CRUD operation or element visibility.
     */
    public bool $isCreateMode = false;

    /**
     * Finds the model instance with the given id and set it to the `form`
     * property.
     *
     * @param int $id The id of the model instance to find
     * @return void
     */
    public function edit(int|string $id): void
    {
        if (!isset($this->model)) {
            throw new \Exception('Property $model is not set in ' . __CLASS__ . ". ---- Eg. protected \$model = User::class;");
        }

        $model = $this->model::find($id);

        $this->setModel($model);
    }

    /**
     * Validates the form data and persists the model.
     *
     * If the model already exists, it is updated with the validated data.
     * If the model does not exist, a new model is created with the validated data.
     *
     * If a 'beforePersist' method exists, it is called before persisting the model.
     *
     * @return void
     */
    public function save(): void
    {
        $validatedData = $this->validate();

        // This might be redundant as a blank model is always created in the 'create' method.
        $model = $this->editing;

        method_exists($this, 'beforePersist') ? $this->beforePersist($validatedData) : null;

        $model->exists
            ? $model->update($validatedData)
            : $model->create($validatedData);
    }


    /**
     * Validates the form data and either creates a new record or updates an existing one.
     *
     * If an ID is provided, this method updates the corresponding record with
     * the validated form data. If no ID is provided, it creates a new record
     * with the validated form data.
     *
     * @param int|null $id The ID of the record to be updated, or null to create a new record.
     * @return void
     */
    public function saveOrNew($id = null): void
    {
        $this->validatedData = $this->validate();
        $this->model::updateOrCreate(['id' => $this->editing->id], $this->validatedData);
        $this->reset('isCreateMode', 'editing');
        // $this->editing = null;
    }

    /**
     * Set form properties from a given model.
     *
     * @param Model $model The model to get properties from.
     * @return void
     */
    protected function setFormProperties(Model $model): void
    {
        /**
         * This loop iterates over each attribute of the model. For each
         * attribute, it checks if a property with the same name exists in the
         * current object. If such a property exists, it assigns the value of
         * the attribute to the property. If the attribute's value is null, it
         * assigns an empty string to the property.
         */

        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value ?? '';
            }
        }
    }

    /**
     * This method sets the form to create mode, creates a new model instance,
     * and sets the form's model to the new instance.
     *
     * @return void
     */
    public function create(): void
    {
        $this->setCreateMode();
        $newModel = $this->createNewModelInstance();
        $this->setModel($newModel);
    }

    /**
     * Create a new instance of the model.
     *
     * @param array $data The data to be used to create the model instance.
     * @return Model The newly created model instance.
     */
    protected function createNewModelInstance(array $data = []): Model
    {
        $data =  $this->initialData ?? $data ?? [];
        $model = $this->model::make($data);

        // Set the sort order to one more than the current maximum in the table,
        // NOT the collection. I really don't see the problem with this because
        // as long as the item is added to the end of the collection it will be
        // in the correct order.
        if (Schema::hasColumn($model->getTable(), 'sort_order')) {
            $model->sort_order = $this->model::max('sort_order') + 1;
        }

        return $model;
    }

    /**
     * Resets the form and related state.
     *
     * @return void
     */
    public function resetForm(): void
    {
        $this->editing = null;
        $this->isCreateMode = false;
    }

    /**
     * Get the model instance that is currently being edited.
     *
     * @return Model The model instance being edited.
     */
    // NK?? What is using this??
    public function getEditingModel(): bool|Model
    {
        return $this->editing ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | FILE UPLOAD METHODS
    |--------------------------------------------------------------------------
    |
    |
    */
    private function handleFormUpload(?UploadedFile $file, array &$validatedData, string $dbColumn): void
    {
        if (!isset($this->storage['disk']) || !isset($this->storage['path'])) {
            throw new \Exception(__CLASS__ . ' - The storage disk and path must be set in the $storage property');
        }

        // Purge null values from the validatedData array. This is to avoid overwriting
        // existing data with null values during the model update process.
        if (array_key_exists('tmpUpload', $validatedData) && $validatedData['tmpUpload'] === null) {
            unset($validatedData['tmpUpload']);
            return;
        }

        if ($file) {
            // delete the previous file if it exists
            tap($this->editing->$dbColumn, function ($previous) {
                if ($previous) {
                    Storage::disk($this->storage['disk'])->delete($previous);
                }
            });

            /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
            $fileInfo = FileManagement::saveWithUnique($file, $this->storage['path'], $this->storage['disk']);
            $validatedData[$dbColumn] = $fileInfo->path();
            unset($validatedData['tmpUpload']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Resets previous form errors and sets the form to create mode.
     *
     * @return void
     */
    protected function setCreateMode(): void
    {
        $this->resetErrorBag();
        $this->isCreateMode = true;
        $this->editingId = false;
    }

    /**
     * Resets previous form errors, sets the form to edit mode, and sets the
     * 'editingId' to the ID of the model to be edited.
     *
     * @param int $id The ID of the model to be edited.
     * @return void
     */
    protected function setEditMode($id): void
    {
        $this->resetErrorBag();
        $this->isCreateMode = false;
        $this->editingId = $id;
    }
}
