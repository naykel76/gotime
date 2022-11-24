<?php

namespace Naykel\Gotime\Http\Livewire;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

trait WithImagePicker

{
    use WithFileUploads;

    /**
     * uploaded file from input
     * @var mixed
     */
    public $upload;

    /**
     * current resource image, can be null
     * @var mixed
     */
    public $currentImage;


    public function updatedUpload()
    {
        $this->validate([
            'upload' => 'image|mimes:jpeg,png,svg,jpg,gif|max:2048',
        ]);
    }

    /**
     * Upload given file and delete old one if exists
     * @param UploadedFile $file
     * @param mixed $disk (local storage disk)
     * @param string $dbField e.g. 'image_name' or 'avatar'
     * @return void
     */
    public function updateUploadedImage(UploadedFile $file, $disk, $dbField = 'image_name'): void
    {
        tap($this->editing->image_name, function ($previous) use ($file, $disk, $dbField) {
            $this->editing->forceFill([
                $dbField => $file->store('/', $disk)
            ])->save();

            if ($previous) {
                Storage::disk($disk)->delete($previous);
            }
        });
    }


    // FUTURE ME! THIS WILL DELETE THE IMAGE BUT IT WILL NOT
    // UPDATE THE DATABASE FIELD BECAUSE IT IS SENT IN THE MAIN
    // CLASS

    // public function deleteImage($img)
    // {
    //     Storage::disk($this->disk)->delete($img);
    // }
}
