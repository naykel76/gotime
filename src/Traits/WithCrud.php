<?php

namespace Naykel\Gotime\Traits;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait WithCrud
{
    use WithFileUploads;

    /**
     * Flag to show or hide modal
     */
    public bool $showModal = false;

    /**
     * Id of the item to be actioned
     */
    public bool|int $actionItemId = false;

    /**
     *
     */
    public bool $isPublished;

    /**
     * Livewire temporary file upload
     * @var mixed
     */
    public $tmpUpload;

    /**
     * Nested items for current resource to be created on save
     */
    public array $nestedItems = [];

    /**
     * Nested items to be deleted from database on save
     */
    public array $removeItems = [];

    /**
     * Create model instance of the current resource and set default values.
     */
    protected function makeBlankModel()
    {
        return self::$model::make($this->initialData);
    }

    /**
     * Edit the selected model (ajax method)
     */
    public function edit($id): void
    {
        $this->editing = self::$model::findOrFail($id);
        $this->showModal = true;
    }

    /**
     * Create blank model with default values (ajax method)
     */
    public function create(): void
    {
        $this->editing = $this->makeBlankModel();
        $this->showModal = true;
    }

    /**
     * Persist and manage before and after save actions including redirect
     * @return void
     */
    public function save(string $redirectAction = null)
    {
        $this->validate();

        $this->beforePersistHook();
        $this->editing->save();
        $this->afterPersistHook();

        // this fires a little quick
        $this->dispatchBrowserEvent('notify', ($this->message ?? 'Saved!'));

        // the action is only required on the first save to redirect to the
        // edit form if need be. I am not sure how this will go with livewire
        // only modal components
        $this->handleRedirect($redirectAction);

        $this->showModal = false;
        // $this->emit('refreshComponent');
    }

    public function delete($id, $redirectAction = null): void
    {
        self::$model::find($id)->delete();
        $this->reset('actionItemId');
        $this->emit('refreshComponent');

        $redirectAction ? $this->handleRedirect($redirectAction) : null;
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
     * Set the id of the item to be actioned
     */
    public function setActionItemId($id): void
    {
        $this->actionItemId = $id;
    }

    public function handleRedirect($action)
    {
        switch ($action) {
            case 'save_stay':
                // this feels a bit hacky, but if there is not slug field or it is null it should work fine
                return redirect(route("$this->routePrefix.edit", ($this->editing->slug ?? $this->editing->id)));
                break;
            case 'save_close':
                return redirect(route("$this->routePrefix.index"));
                break;
            case 'save_new':
                return redirect(route("$this->routePrefix.create"));
                break;
            case 'delete_close':
                return redirect(route("$this->routePrefix.index"));
                break;
        }
    }

    /**
     *
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

        // i am not sure this a good place to reset but it seems to work \o/
        $this->dispatchBrowserEvent('pondReset');
        $this->reset(['tmpUpload']);
    }

    /**
     * Automatically assign next highest sort oder value to current editing resource
     * @return void
     */
    protected function setSortOrder($collection): void
    {
        if ($this->editing->sort_order === '' || $this->editing->sort_order === null) {
            $this->editing->sort_order = addToEnd($collection);
        }
    }

    /**
     * Set the title based on the route prefix
     */
    protected function setTitle(): string
    {
        return (isset($this->editing->id) ? 'Edit ' : 'Create ') .
            Str::singular(Str::title(dotLastSegment($this->routePrefix)));
    }

    protected function handlePublishedStatus(): void
    {
        if ($this->isPublished && !$this->editing->isPublished()) {
            $this->editing->published_at = now();
        } elseif (!$this->isPublished) {
            $this->editing->published_at = null;
        }
    }

    /**
     * Perform additional tasks before persisting the database
     */
    protected function beforePersistHook(): void
    {
    }

    /**
     * Perform additional tasks after persisting the database
     */
    protected function afterPersistHook(): void
    {
    }


    /*
    |--------------------------------------------------------------------------
    | NESTED ITEM METHODS
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    /**
     * Add empty row to items array for nested resource items
     */
    public function addEmptyRow(): void
    {
        $this->nestedItems[] = '';
    }

    /**
     * Remove from $items array and delete from database on save
     */
    public function removeItem(int $index): void
    {
        // When id exists (not index), then the item comes from the database.
        // Items are not removed from the database until we click the save
        // button, so store them in an array until it's go time!
        isset($this->nestedItems[$index]['id'])
            ? array_push($this->removeItems, $this->nestedItems[$index]['id'])
            : null;

        unset($this->nestedItems[$index]);

        // reindex and reset items to keep editors happy when removing items
        $this->nestedItems = array_values($this->nestedItems);
    }
}
