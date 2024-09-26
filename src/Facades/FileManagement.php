<?php

namespace Naykel\Gotime\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string saveWithUnique(\Illuminate\Http\UploadedFile $file, string $path, string $disk = 'public')
 * @method static string getUniqueFilename(\Illuminate\Http\UploadedFile $file)
 * @method static void replaceInFile(string $search, string $replace, string $path)
 * @method static bool stringInFile(string $path, string $search)
 *
 * @see \Naykel\Gotime\Services\FileManagementService
 */
class FileManagement extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filemanagement';
    }
}
