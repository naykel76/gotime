<?php

namespace Naykel\Gotime\Services;

use Illuminate\Http\UploadedFile;

class FileManagementService
{
    /**
     * Save the file with a unique filename.
     */
    public function saveWithUnique(UploadedFile $file, string $path, string $disk = 'public'): string
    {
        $name = $this->getUniqueFilename($file);
        $path = $file->storeAs($path, $name, $disk);

        return $path;
    }

    /**
     * Get a unique filename for the file.
     */
    public function getUniqueFilename(UploadedFile $file): string
    {
        return now()->timestamp . '-' . str_replace(' ', '-', $file->getClientOriginalName());
    }
}
