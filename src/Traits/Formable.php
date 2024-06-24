<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Naykel\Gotime\Facades\FileManagement;

trait Formable
{

// I dont think it is a good idea to tightly couple with the editing object

    /**
     * @var Model The form object model.
     */
    public ?Model $editing;

    /**
     * @var bool A flag used to toggle various CRUD operation or element visibility.
     */
    public bool $isCreateMode = false;

    /**
     * Set form properties for a given model. 
     * 
     * This method iterates over the model's attributes and sets the form's
     * properties to the corresponding values if the property exists in the form
     * object.
     *
     * @param Model $model The model to get properties from.
     * @return void
     */
    protected function setFormProperties(Model $model): void
    {
        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value ?? '';
            }
        }
    }


    /**
     * Get the model instance that is currently being edited.
     *
     * @return Model The model instance being edited.
     */
    public function getModel(): bool|Model
    {
        return $this->editing ?? false;
    }
}





//     /**
//      * Validates the form data and either creates a new record or updates an existing one.
//      *
//      * If an ID is provided, this method updates the corresponding record with
//      * the validated form data. If no ID is provided, it creates a new record
//      * with the validated form data.
//      *
//      * @param int|null $id The ID of the record to be updated, or null to create a new record.
//      * @return void
//      */
//     public function saveOrNew($id = null): void
//     {
//         $this->validatedData = $this->validate();
//         $this->model::updateOrCreate(['id' => $this->editing->id], $this->validatedData);
//         $this->reset('isCreateMode', 'editing');
//     }

//     /**
//      * This method sets the form to create mode, creates a new model instance,
//      * and sets the form's model to the new instance.
//      *
//      * @return void
//      */
//     public function create(array $data = []): Model
//     {
//         $this->setCreateMode();

//         $newModel = $data
//             ? $this->createModelInstance($data)
//             : $this->createModelInstance();

//         // NK::?? is it a good idea to set the model here? should i just return the model?
//         $this->setModel($newModel);

//         return $newModel;
//     }

//     /**
//      * Create a new instance of the model.
//      *
//      * @param array $data The data to be used to create the model instance.
//      * @return Model The newly created model instance.
//      */
//     public function createModelInstance(array $data = []): Model
//     {
//         $data =  $this->initialData ?? $data ?? [];
//         $model = $this->model::make($data);

//         // Set the sort order to one more than the current maximum in the table,
//         // NOT the collection. I really don't see the problem with this because
//         // as long as the item is added to the end of the collection it will be
//         // in the correct order.
//         if (Schema::hasColumn($model->getTable(), 'sort_order')) {
//             $model->sort_order = $this->model::max('sort_order') + 1;
//         }

//         return $model;
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | FILE UPLOAD METHODS
//     |--------------------------------------------------------------------------
//     |
//     |
//     */
//     private function handleFormUpload(?UploadedFile $file, array &$validatedData, string $dbColumn): void
//     {
//         if (!isset($this->storage['disk']) || !isset($this->storage['path'])) {
//             throw new \Exception(__CLASS__ . ' - The storage disk and path must be set in the $storage property');
//         }

//         // Purge null values from the validatedData array. This is to avoid overwriting
//         // existing data with null values during the model update process.
//         if (array_key_exists('tmpUpload', $validatedData) && $validatedData['tmpUpload'] === null) {
//             unset($validatedData['tmpUpload']);
//             return;
//         }

//         if ($file) {
//             // delete the previous file if it exists
//             tap($this->editing->$dbColumn, function ($previous) {
//                 if ($previous) {
//                     Storage::disk($this->storage['disk'])->delete($previous);
//                 }
//             });

//             /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
//             $fileInfo = FileManagement::saveWithUnique($file, $this->storage['path'], $this->storage['disk']);
//             $validatedData[$dbColumn] = $fileInfo->path();
//             unset($validatedData['tmpUpload']);
//         }
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | HELPER METHODS
//     |--------------------------------------------------------------------------
//     |
//     |
//     */


//     /**
//      * Resets previous form errors and sets the form to create mode.
//      *
//      * @return void
//      */
//     protected function setCreateMode(): void
//     {
//         $this->resetErrorBag();
//         $this->isCreateMode = true;
//         $this->editingId = false;
//     }

//     /**
//      * Resets previous form errors, sets the form to edit mode, and sets the
//      * 'editingId' to the ID of the model to be edited.
//      *
//      * @param int $id The ID of the model to be edited.
//      * @return void
//      */
//     protected function setEditMode($id): void
//     {
//         $this->resetErrorBag();
//         $this->isCreateMode = false;
//         $this->editingId = $id;
//     }

//     /**
//      * Resets the form and related state.
//      *
//      * @return void
//      */
//     public function resetForm(): void
//     {
//         $this->editing = null;
//         $this->isCreateMode = false;
//     }
// }