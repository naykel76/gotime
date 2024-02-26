<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Naykel\Gotime\Facades\FileManagement;

trait Crudable
{
    use WithFileUploads;
    use Hookable;

    /**
     * @var array Holds the validated form data.
     */
    public array $validatedData = [];

    /**
     * @var bool|int Represents the ID of the item to be actioned.
     */
    public $actionId = false;

    /**
     * @var bool A flag used to toggle various CRUD operation or elements.
     */
    public bool $isCreateMode = false;

    /**
     * Used as a flag for component visibility. Eg, show/hide modals, forms etc.
     *
     * @var bool|int ID of the item to edit, or false if none is selected.
     */
    public $editingId = false;

    /**
     * The title of the page.
     *
     * @var string
     */
    public string $pageTitle = '';

    /**
     * Finds the model instance with the given id and set it to the `form`
     * property.
     *
     * The method listens for the 'set-editing-item' event generally
     * dispatched from a parent class.
     *
     * @param int $id The id of the model instance to find
     * @return void
     */
    #[On('set-editing-item')]
    public function edit(int|string $id): void
    {
        $this->setEditMode($id);
        if (!isset($this->model)) {
            throw new \Exception('Property $model is not set in ' . __CLASS__ . ". ---- Eg. protected \$model = User::class;");
        }
        $this->form->setModel($this->model::findOrFail($id));
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
        $this->form->setModel($newModel);
    }

    /**
     * Validates the form data and either creates a new record or updates an * existing one.
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
        $editingId = $id ?? $this->form->getEditingModel()->id;
        $this->beforeValidateHook();
        $this->validatedData = $this->validate();
        $this->beforePersistHook();
        $this->model::updateOrCreate(['id' => $editingId], $this->validatedData);
        $this->afterPersistHook();
        $this->dispatchEvents();
        $this->resetAndReload();
    }

    public function save(): void
    {

        // if sort_order and new record the add to end of collection
        $this->beforeValidateHook();
        $this->validatedData = $this->form->validate();
        $this->beforePersistHook();

        $model = $this->form->getEditingModel();

        $model->exists
            ? $this->update($model, $this->validatedData)
            : $this->store($model, $this->validatedData);

        $this->afterPersistHook();

        $this->reset('validatedData', 'selected');
        $this->resetFlags();

        $this->dispatch('notify', ('Saved!'));
        $this->dispatch('item-saved');
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
        $this->setSortOrder($model);
        return $model;
    }

    /**
     * Set the sort order of the model if it has a 'sort_order' field.
     *
     * @param Model $model The model whose sort order is to be set.
     * @return void
     */
    protected function setSortOrder(Model $model): void
    {
        // This will add the highest sort order in the table, not the collection
        // I really don't see the problem with this because as long as the item is
        // added to the end of the collection it will be in the correct order.
        if (Schema::hasColumn($model->getTable(), 'sort_order')) {
            $model->sort_order = $this->model::max('sort_order') + 1;
        }
    }

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

    /**
     * Resets the form, component and reloads the items.
     *
     * @return void
     */
    protected function resetAndReload()
    {
        if (method_exists($this, 'loadItems')) {
            $this->loadItems();
        }
        $this->resetAll();
    }

    /**
     * Resets the form and related state.
     *
     * @return void
     */
    protected function resetAll(): void
    {
        $this->reset('isCreateMode');
        $this->form->editing = null;
    }

    /**
     * Resets the form and related state.
     *
     * This method, acting as an alias for `resetAll`, provides a more intuitive
     * name in certain contexts.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->resetAll();
    }

    protected function dispatchEvents()
    {
        $this->dispatch('item-saved');
        $this->dispatch('notify', 'Updated successfully!');
    }

    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //


    /**
     * @var string The name of the view file to render.
     */
    public $selected = '';

    public function setSelected(string $name)
    {
        $this->selected = $name;
    }



    /**
     * @var bool Controls the visibility of a modal.
     */
    public bool $showModal = false;







    /**
     * Create a new record in the database.
     *
     * @param Model $model The model instance
     * @param array $validatedData The validatedData data to be stored
     * @return void
     */
    public function store(Model $model, array $validatedData): void
    {
        $model::create($validatedData);
    }

    /**
     * Update an existing record in the database.
     *
     * @param Model $model The model instance
     * @param array $validatedData The validatedData data to be updated
     * @return void
     */
    public function update(Model $model, array $validatedData): void
    {
        $model->update($validatedData);
    }



    /**
     * Delete a model instance from the database and and optionally handle redirection.
     *
     * This delete method is tightly coupled to the FormObjects model instance.
     *
     * @param string|null $redirectAction The action to redirect to after deletion. If null, no redirection is performed.
     * @return void
     */
    public function delete(string $id = null): void
    {
        // make sure that some form of model exists
        if (!$this->editingModelExists() && !isset($this->model)) {
            throw new \Exception('The is no `model` defined or `editing` model set');
        }

        if ($this->editingModelExists()) {
            $this->form->getEditingModel()->delete();
        } else {
            $this->model::find($this->actionId)->delete();
        }

        $this->reset('actionId');
        $this->dispatch('deleted');
    }

    /*
    |--------------------------------------------------------------------------
    | FILE UPLOADS
    |--------------------------------------------------------------------------
    |
    */

    protected function handleUpload($file, string $disk = 'public', string $dbField = 'image', $withOriginalName = false): void
    {
        tap($this->editing->$dbField, function ($previous) use ($file, $disk, $dbField, $withOriginalName) {

            if ($previous) {
                Storage::disk($disk)->delete($previous);
            }

            $filename = $file->getClientOriginalName();

            $this->editing->forceFill([

                $dbField => $withOriginalName
                    ? $file->storeAs('/', $filename, $disk)
                    : $file->store('/', $disk)

            ])->save();
        });
    }

    private function handleFile(?UploadedFile $file, array &$validatedData, string $dbColumn): void
    {
        // Use the tap method to delete the previous file if it exists
        tap($this->form->editing->$dbColumn, function ($previous) {
            if ($previous) {
                Storage::disk($this->storage['disk'])->delete($previous);
            }
        });

        // Purge null values from the validatedData array. This is to avoid overwriting
        // existing data with null values during the model update process.
        if (array_key_exists('tmpUpload', $validatedData) && $validatedData['tmpUpload'] === null) {
            unset($validatedData['tmpUpload']);
            return;
        }

        if ($file) {
            /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
            $fileInfo = FileManagement::saveWithUnique($file, $this->storage['path'], $this->storage['disk']);
            $validatedData[$dbColumn] = $fileInfo->path();
            unset($validatedData['tmpUpload']);
            $this->dispatch('pondReset');
        }
    }



    /**
     * Renders the view for the current component.
     *
     * @return \Illuminate\View\View The view instance.
     */
    public function render()
    {
        return view("livewire.$this->view")
            ->layout(\Naykel\Gotime\View\Layouts\AppLayout::class, [
                'pageTitle' => $this->pageTitle,
                'layout' => 'admin'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    |
    |
    */

    public function resetFlags()
    {
        $this->reset('isCreateMode', 'showModal', 'editingId', 'actionId');
    }

    public function setActionId($id): void
    {
        $this->actionId = $id;
    }

    /**
     * Checks if the model exists. If it does, the form will be in 'edit'
     * mode. Otherwise, it will be in 'create' mode.
     */
    private function editingModelExists(): bool
    {
        $editingModel = $this->form->getEditingModel();
        return $editingModel ? $editingModel->exists : false;
    }

    /**
     * Sets the page title based on the current route.
     *
     * @param string $routePrefix The prefix of the current route.
     * @return string The page title.
     */
    private function setPageTitle(string $routePrefix): string
    {
        $action = $this->editingModelExists() ? 'Edit ' : 'Create ';
        $lastSegment = dotLastSegment($routePrefix);
        $exclude = ['media']; // prevent singular conversion (media->medium)

        if (in_array($lastSegment, $exclude)) {
            return $action . Str::title($lastSegment);
        }

        return $action . Str::singular(Str::title($lastSegment));
    }
}
