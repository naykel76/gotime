<?php

namespace Naykel\Gotime\Traits;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

trait WithCrud
{
    use WithFileUploads;

    public $config = ['dbField' => 'image'];
    public $tmpUpload;
    public $showModal;

    public $confirmingActionId = false;

    public function cancel(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function setConfirmAction($id)
    {
        $this->confirmingActionId = $id;
    }

    /**
     * Create model instance of the current resource and set default values.
     */
    public function makeBlankModel()
    {
        return self::$model::make($this->initialData);
    }

    /**
     * Edit the selected model
     */
    public function edit($id): void
    {
        $this->editing = self::$model::find($id);
        $this->showModal = true;
    }

    /**
     * Create blank model with default values
     */
    public function create(): void
    {
        $this->editing = $this->makeBlankModel();
        $this->showModal = true;
    }

    public function save($redirectAction = null)
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

    /**
     * Save and return new instance
     * @return void
     */
    public function saveCreate()
    {
        $this->validate();
        $this->beforePersistHook();
        $this->editing->save();
        $this->afterPersistHook();

        return $new;
    }

    public function delete($id, $redirectAction = null): void
    {
        self::$model::find($id)->delete();
        $this->confirmingActionId = false;
        $this->emit('refreshComponent');

        $this->handleRedirect($redirectAction);
    }



    public function handleRedirect($action)
    {
        switch ($action) {
            case 'save_stay':
                // this feels a bit hacky, but if there is not slug field or it is null it should work fine
                return redirect(route("admin.$this->resource.edit", ($this->editing->slug ?? $this->editing->id)));
                break;
            case 'save_close':
                return redirect(route("admin.$this->resource.index"));
                break;
            case 'save_new':
                return redirect(route("admin.$this->resource.create"));
                break;
            case 'delete_close':
                return redirect(route("admin.$this->resource.index"));
                break;
        }
    }

    public function handleUpload($file, $disk, $dbField, $hashFilename = false): void
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
