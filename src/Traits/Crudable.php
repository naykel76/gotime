<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Naykel\Gotime\Facades\FileManagement;

trait Crudable
{
    /**
     * @var UploadedFile Temporary file upload
     */
    // Do not add validation rules to this property. Let filepond handle for
    // now, or add separately in the handleUpload method if needed.
    public ?UploadedFile $tmpUpload = null;

    /**
     * Validates the form data and persists the model.
     *
     * If the model already exists, it is updated with the validated data.
     * If the model does not exist, a new model is created with the validated data.
     *
     * @return Model The model that was updated or created. This is the same model as $this->editing.
     */
    public function save(): Model
    {
        $validatedData = $this->validate();

        // upload file and add to validated data array
        if ($this->tmpUpload) {
            $this->handleUpload($this->tmpUpload, $validatedData, $this->storage['dbColumn']);
        }

        method_exists($this, 'beforePersistHook') && $this->beforePersistHook($validatedData);

        $this->editing = $this->editing::updateOrCreate(['id' => $this->editing->id], $validatedData);

        $this->component->dispatch('saved');

        return $this->editing;
    }

    /**
     * @param  array  $validatedData  @param string $dbColumn @return void
     */
    private function handleUpload(?UploadedFile $file, array &$validatedData, string $dbColumn): void
    {
        // delete the previous file if it exists
        tap($this->editing->$dbColumn, function ($previous) {
            if ($previous) {
                Storage::disk($this->storage['disk'])->delete($previous);
            }
        });

        /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
        $fileInfo = FileManagement::saveWithUnique($file, $this->storage['path'], $this->storage['disk']);
        $validatedData[$dbColumn] = $fileInfo->path();

        $this->component->dispatch('pondReset');
    }

    /**
     * Validates the storage settings.
     *
     * @throws \Exception If disk or path is not set.
     */
    private function validateStorageSettings()
    {
        if (! isset($this->storage['disk']) || ! isset($this->storage['path'])) {
            throw new \Exception(__CLASS__ . ' - The storage disk and path must be set in the $storage property');
        }
    }
}
