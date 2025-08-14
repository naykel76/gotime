<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Naykel\Gotime\Facades\FileManagement;

/**
 * This trait is designed for Gotime Livewire components that are part of a CRUD
 * system. It is tightly coupled with the Formable trait, which is responsible
 * for managing the form state and validation.
 *
 * - $editing is the model being edited or created which is always available
 *   thought the Formable trait.
 */
trait Crudable
{
    /**
     * @var UploadedFile Temporary file upload
     */
    public ?UploadedFile $tmpUpload = null;

    /**
     * Validates the form data and persists the model.
     *
     * If the model already exists, it is updated with the validated data. If
     * the model does not exist, a new model is created with the validated data.
     *
     * @return Model The model that was updated or created. This is the same
     *               model as $this->editing.
     */
    public function save(): Model
    {
        $validatedData = $this->validate();

        $storageConfig = $this->getStorageConfig();

        // NK::TD If the transaction fails and rolls back, uploaded files are
        // not reverted automatically. Implement additional cleanup logic.
        $this->editing = DB::transaction(function () use (&$validatedData, $storageConfig) {

            if ($this->shouldHandleUpload()) {
                $this->handleUpload($this->tmpUpload, $validatedData, $storageConfig);
            }

            if (method_exists($this, 'beforePersistHook')) {
                $this->beforePersistHook($validatedData);
            }

            return $this->editing::updateOrCreate(
                ['id' => $this->editing->id],
                $validatedData
            );
        });

        return $this->editing;
    }

    /**
     * Handle the file upload and update the validated data.
     */
    private function handleUpload(UploadedFile $file, array &$validatedData, array $storageConfig = []): void
    {

        $dbColumn = $storageConfig['dbColumn'];

        // delete the previous file if it exists
        tap($this->editing->$dbColumn, function ($previous) use ($storageConfig) {
            if ($previous) {
                Storage::disk($storageConfig['disk'])->delete($previous);
            }
        });

        /** @var \Naykel\Gotime\DTO\FileInfo $fileInfo */
        $fileInfo = FileManagement::saveWithUnique($file, $storageConfig['path'], $storageConfig['disk']);
        $validatedData[$storageConfig['dbColumn']] = $fileInfo->path();

        $this->component->dispatch('file-upload-completed');
    }

    // ------------------------------------------------------------------------------------
    // Helpers and Guards
    // ------------------------------------------------------------------------------------

    private function getStorageConfig(): array
    {
        return array_merge([
            'dbColumn' => 'file_name',
            'disk' => 'public',
            'path' => '',
        ], $this->storage ?? []);
    }

    /**
     * Check if upload should be handled.
     */
    private function shouldHandleUpload(): bool
    {
        return $this->tmpUpload !== null;
    }
}
