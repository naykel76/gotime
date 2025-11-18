<?php

namespace Naykel\Gotime\Services;

use Illuminate\Http\UploadedFile;
use Naykel\Gotime\DTO\FileInfo;

class FileManagementService
{
    /**
     * Save the file with a unique filename.
     */
    public function saveWithUnique(UploadedFile $file, string $path, string $disk = 'public'): \Naykel\Gotime\DTO\FileInfo
    {
        $filename = $this->getUniqueFilename($file);
        $file->storeAs($path, $filename, $disk);

        return new FileInfo($path, $filename, $disk);
    }

    /**
     * Generate a unique filename for the uploaded file.
     *
     * This method concatenates the current timestamp and the original filename
     * of the uploaded file, replacing any spaces in the filename with dashes.
     * This ensures that the filename is unique and filesystem-friendly.
     *
     * @param  UploadedFile  $file  The file for which to generate a unique filename.
     * @return string The unique filename.
     */
    public function getUniqueFilename(UploadedFile $file): string
    {
        return now()->timestamp . '-' . str_replace(' ', '-', $file->getClientOriginalName());
    }

    /**
     * Replace a given string within a given file.
     */
    public function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * A given string exists within a given file.
     */
    public function stringInFile(string $path, string $search): bool
    {
        return str_contains(file_get_contents($path), $search);
    }
}
