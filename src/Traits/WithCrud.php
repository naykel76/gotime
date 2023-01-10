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
     * Create model instance of the current resource and set default values.
     */
    public function makeBlankModel()
    {
        return self::$model::make($this->initialData);
    }

    /**
     * Edit the selected model (non-route method)
     */
    public function edit($id): void
    {
        $this->editing = self::$model::find($id);
        $this->showModal = true;
    }

    /**
     * Create blank model with default values (non-route method)
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
        $this->emit('refreshComponent');
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
    public function handleUpload($file, string $disk = 'public', string $dbField = 'image', $hashFilename = false): void
    {
        tap($this->editing->$dbField, function ($previous) use ($file, $disk, $dbField, $hashFilename) {

            if ($previous) {
                Storage::disk($disk)->delete($previous);
            }

            $filename = $file->getClientOriginalName();

            $this->editing->forceFill([

                $dbField => $hashFilename ?
                    $file->storeAs('/', $filename, $disk) :
                    $file->store('/', $disk)

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
    public function setTitle(): string
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
     * @return void
     */
    protected function beforePersistHook(): void
    {
    }

    /**
     * Perform additional tasks after persisting the database
     * @return void
     */
    protected function afterPersistHook(): void
    {
    }
}
