<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Naykel\Gotime\Facades\FileManagement;

trait Crudable
{
    use WithFileUploads;
    use Hookable;

    public string $pageTitle = '';

    /**
     * @var array Array of validated form data.
     */
    public array $validatedData = [];

    /**
     * A boolean property to control the visibility of a modal.
     *
     * Note: Not all classes using this trait will require a modal. It's
     * important to understand that even though this property is set, it may not
     * always result in a modal being displayed, as that depends on if the modal
     * has been included in the view.
     *
     * @var bool
     */
    public bool $showModal = false;

    /**
     * The ID of the item to be actioned. This is a placeholder used as an
     * identifier for various actions. If no item is selected for action, the
     * default value is false.
     *
     * @var bool|int
     */
    public $actionId = false;

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
        $this->showModal = true;
    }

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

    public function save(): void
    {
        $this->beforeValidateHook();
        $this->validatedData = $this->form->validate();
        $this->beforePersistHook();

        $model = $this->form->getEditingModel();

        $model->exists
            ? $this->update($model, $this->validatedData)
            : $this->store($model, $this->validatedData);

        $this->afterPersistHook();

        $this->reset('validatedData');

        $this->showModal = false;

        $this->dispatch('notify', ('Saved!'));
        $this->dispatch('refresh-items');

        // handleRedirect($redirectAction);
    }


    /**
     * Delete a model instance from the database and and optionally handle redirection.
     *
     * This delete method is tightly coupled to the FormObjects model instance.
     *
     * @param string|null $redirectAction The action to redirect to after deletion. If null, no redirection is performed.
     * @return void
     */
    public function delete(string $redirectAction = null): void
    {
        $this->form->getEditingModel()->delete();

        $this->reset('actionId');

        if ($redirectAction) {
            handleRedirect($this->routePrefix, $redirectAction);
        }
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
     * Cancel actions for modals and full page components
     * @return void
     */
    public function cancel(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
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

    public function setActionId($id): void
    {
        $this->actionId = $id;
    }

    /**
     * Sets the sort order of the form.
     *
     * If the sort order is not set or is an empty string, it assigns the last
     * position in the collection to the sort order.
     *
     * @param Collection $collection The collection to get the last position
     * from.
     * @return void
     */
    protected function setSortOrder(Collection $collection): void
    {
        if (!isset($this->form->sort_order) || $this->form->sort_order === '') {
            $this->form->sort_order = addToEnd($collection);
        }
    }

    /**
     * Checks if the model exists. If it does, the form will be in 'edit'
     * mode. Otherwise, it will be in 'create' mode.
     */
    private function editingModelExists(): bool
    {
        return $this->form->getEditingModel()->exists;
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
