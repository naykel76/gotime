<?php

namespace Naykel\Gotime\Services;

use Illuminate\Http\UploadedFile;
use Naykel\Gotime\DTO\FileInfo;

class FileManagementService
{
    /**
     * Save the file with a unique filename.
     *
     * * @return \Naykel\Gotime\DTO\FileInfo
     */
    public function saveWithUnique(UploadedFile $file, string $directory, string $disk = 'public'): \Naykel\Gotime\DTO\FileInfo
    {
        $filename = $this->getUniqueFilename($file);
        $file->storeAs($directory, $filename, $disk);

        return new FileInfo($directory, $filename, $disk);
    }

    /**
     * Get a unique filename for the file.
     */
    public function getUniqueFilename(UploadedFile $file): string
    {
        return now()->timestamp . '-' . str_replace(' ', '-', $file->getClientOriginalName());
    }
}
