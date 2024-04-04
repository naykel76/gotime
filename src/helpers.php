<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;

// ------------------------------------------------------------------
// -- FILES AND FILESYSTEM --
// ------------------------------------------------------------------

if (!function_exists('getJsonFile')) {
    function getJsonFile(string $path, bool $returnAsArray = false): array|object
    {
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), $returnAsArray);
        }

        throw new FileNotFoundException("File does not exist at path {$path}.");
    }
}

if (!function_exists('getFile')) {

    function getFile(string $path): string|false
    {
        if (file_exists($path)) {
            return file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}.");
    }
}
