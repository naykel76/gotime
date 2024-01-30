<?php

namespace Naykel\Gotime\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string saveWithUnique(\Illuminate\Http\UploadedFile $file, string $path, string $disk = 'public')
 * @method static string getUniqueFilename(\Illuminate\Http\UploadedFile $file)
 *
 * @see \Naykel\Gotime\Services\FileManagement
 */
class FileManagement extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filemanagement';
    }
}
