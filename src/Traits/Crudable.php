<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Naykel\Gotime\Facades\FileManagement;

trait Crudable
{
    /**
     * Validates the form data and persists the model.
     *
     * If the model already exists, it is updated with the validated data.
     * If the model does not exist, a new model is created with the validated data.
     *
     * @return Model The model that was updated or created. This is the same model as $this->editing.
     */

    //  the action is used to determine what to do after the save
    //  save_close, save_new, delete_close


    // the routePrefix is an integral part of the crud system. I am not sure if I should be refering to this->routePrefix in the trait or if it should be passed in as a parameter, or a getter.

    // public function save(): Model
    // {
    //     $validatedData = $this->validate();

    //     method_exists($this, 'beforePersist') ? $this->beforePersist($validatedData) : null;

    //     if ($this->editing->exists) {
    //         $this->editing->update($validatedData);
    //     } else {
    //         $this->editing = $this->editing->create($validatedData);
    //     }

    //     return $this->editing;
    // }

    public function save($action = ''): Model
    {
        $validatedData = $this->validate();

        // App\Models\User
        $editing = $this->getEditingModel();

        method_exists($this, 'beforePersist') ? $this->beforePersist($validatedData) : null;

        dd($editing);
        $editing
            ? $editing->update($validatedData)
            : $editing = $editing->create($validatedData);

        $this->dispatch('notify', ($this->message ?? 'Saved!'));
        // dd($editing);
        // redirect
        // if ($action == 'save_edit') {
        //     handleRedirect($this->routePrefix, $action, $model->id);
        // }

        return $editing;
    }

    /**
     * Get the model instance that is currently being edited.
     *
     * @return Model
     * @throws \Exception
     */
    protected function getEditingModel(): Model
    {
        if (isset($this->form) && isset($this->form->editing)) {
            dd($this->form->editing);
            return $this->form->editing;
        }

        if (isset($this->editing)) {
            return $this->editing;
        }

        throw new \Exception('Editing model not found.');
    }


    // this could arguably be in a separate trait

    /**
     * Handle redirection after a CRUD operation based on the specified action.
     *
     * @param string $routeName The base name of the route to redirect to.
     * @param string $redirectAction The action determining the redirection route. Possible values are 'save_stay', 'save_close', 'save_new', and 'delete_close'.
     * @param null|int|string $param Optional parameter for the route, typically used for specifying an entity ID when editing.
     * @return \Illuminate\Http\RedirectResponse A redirect response to the appropriate route based on the action.
     */
    function handleRedirect(string $routeName, string $redirectAction, int|string $param = null)
    {
        switch ($redirectAction) {
            case 'save_stay':
                // this feels a bit hacky, but if there is no slug field or it is null it should work fine
                return redirect(route("$routeName.edit", $param));
                break;
            case 'save_close':
                return redirect(route("$routeName.index"));
                break;
            case 'save_new':
                return redirect(route("$routeName.create"));
                break;
            case 'delete_close':
                return redirect(route("$routeName.index"));
                break;
        }
    }
}



    // /**
    //  * Validates the form data and either creates a new record or updates an existing one.
    //  *
    //  * If an ID is provided, this method updates the corresponding record with
    //  * the validated form data. If no ID is provided, it creates a new record
    //  * with the validated form data.
    //  *
    //  * @param int|null $id The ID of the record to be updated, or null to create a new record.
    //  * @return void
    //  */
    // public function saveOrNew($id = null): void
    // {
    //     $this->validatedData = $this->validate();
    //     $this->model::updateOrCreate(['id' => $this->editing->id], $this->validatedData);
    //     $this->reset('isCreateMode', 'editing');
    // }


//     //     /**
//     //      * @var bool A flag used to toggle various CRUD operation or element visibility.
//     //      */
//     //     public bool $isCreateMode = false;

//     // /**
//     //  * Sets the model instance for editing based on the provided ID.
//     //  *
//     //  * @param int $id The id of the model instance to find
//     //  * @return void
//     //  */
//     // public function edit(int|string $id): void
//     // {
//     //     // make sure the model property is set
//     //     if (!isset($this->modelClass)) {
//     //         throw new \Exception('Property $model is not set in ' . __CLASS__ . ". ---- Eg. protected \$model = User::class;");
//     //     }

//     //     $model = $this->model::find($id);

//     //     $this->setModel($model);
//     // }



//     //     /**
//     //      * Validates the form data and persists the model.
//     //      *
//     //      * If the model already exists, it is updated with the validated data.
//     //      * If the model does not exist, a new model is created with the validated data.
//     //      *
//     //      * @return Model The model that was updated or created. This is the same model as $this->editing.
//     //      */





//     //     /**
//     //      * This method sets the form to create mode, creates a new model instance,
//     //      * and sets the form's model to the new instance.
//     //      *
//     //      * @return void
//     //      */
//     //     public function create(array $data = []): Model
//     //     {
//     //         $this->setCreateMode();

//     //         $newModel = $data
//     //             ? $this->createModelInstance($data)
//     //             : $this->createModelInstance();

//     //         // NK::?? is it a good idea to set the model here? should i just return the model?
//     //         $this->setModel($newModel);

//     //         return $newModel;
//     //     }

//     //     /**
//     //      * Create a new instance of the model.
//     //      *
//     //      * @param array $data The data to be used to create the model instance.
//     //      * @return Model The newly created model instance.
//     //      */
//     //     public function createModelInstance(array $data = []): Model
//     //     {
//     //         $data =  $this->initialData ?? $data ?? [];
//     //         $model = $this->model::make($data);

//     //         // Set the sort order to one more than the current maximum in the table,
//     //         // NOT the collection. I really don't see the problem with this because
//     //         // as long as the item is added to the end of the collection it will be
//     //         // in the correct order.
//     //         if (Schema::hasColumn($model->getTable(), 'sort_order')) {
//     //             $model->sort_order = $this->model::max('sort_order') + 1;
//     //         }

//     //         return $model;
//     //     }

//     //     /*
//     //     |--------------------------------------------------------------------------
//     //     | FILE UPLOAD METHODS
//     //     |--------------------------------------------------------------------------
//     //     |
//     //     |
//     //     */
//     //     private function handleFormUpload(?UploadedFile $file, array &$validatedData, string $dbColumn): void
//     //     {
//     //         if (!isset($this->storage['disk']) || !isset($this->storage['path'])) {
//     //             throw new \Exception(__CLASS__ . ' - The storage disk and path must be set in the $storage property');
//     //         }

//     //         // Purge null values from the validatedData array. This is to avoid overwriting
//     //         // existing data with null values during the model update process.
//     //         if (array_key_exists('tmpUpload', $validatedData) && $validatedData['tmpUpload'] === null) {
//     //             unset($validatedData['tmpUpload']);
//     //             return;
//     //         }

//     //         if ($file) {
//     //             // delete the previous file if it exists
//     //             tap($this->editing->$dbColumn, function ($previous) {
//     //                 if ($previous) {
//     //                     Storage::disk($this->storage['disk'])->delete($previous);
//     //                 }
//     //             });

//     //             /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
//     //             $fileInfo = FileManagement::saveWithUnique($file, $this->storage['path'], $this->storage['disk']);
//     //             $validatedData[$dbColumn] = $fileInfo->path();
//     //             unset($validatedData['tmpUpload']);
//     //         }
//     //     }



//     //     /**
//     //      * Resets previous form errors and sets the form to create mode.
//     //      *
//     //      * @return void
//     //      */
//     //     protected function setCreateMode(): void
//     //     {
//     //         $this->resetErrorBag();
//     //         $this->isCreateMode = true;
//     //         $this->editingId = false;
//     //     }

//     //     /**
//     //      * Resets previous form errors, sets the form to edit mode, and sets the
//     //      * 'editingId' to the ID of the model to be edited.
//     //      *
//     //      * @param int $id The ID of the model to be edited.
//     //      * @return void
//     //      */
//     //     protected function setEditMode($id): void
//     //     {
//     //         $this->resetErrorBag();
//     //         $this->isCreateMode = false;
//     //         $this->editingId = $id;
//     //     }

//     //     /**
//     //      * Resets the form and related state.
//     //      *
//     //      * @return void
//     //      */
//     //     public function resetForm(): void
//     //     {
//     //         $this->editing = null;
//     //         $this->isCreateMode = false;
//     //     }
//     // }
// }
