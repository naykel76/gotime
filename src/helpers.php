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

// ------------------------------------------------------------------
// -- URL PATH CONVERSION HELPERS --
// ------------------------------------------------------------------

if (!function_exists('toUrl')) {
    /**
     * Convert dot notation to relative url or path. Same as fromDot()
     */
    function toUrl(string $input): string
    {
        return str_replace('.', '/', ltrim($input, '/'));
    }
}

if (!function_exists('fromDot')) {
    /**
     * Convert dot notation to relative url or path
     */
    function fromDot(string $input): string
    {
        return str_replace('.', '/', ltrim($input, '/'));
    }
}

if (!function_exists('toDot')) {
    /**
     * Convert url or path to dot notation
     */
    function toDot(string $input): string
    {
        return str_replace('/', '.', ltrim($input, '.'));
    }
}

if (!function_exists('numSegments')) {
    /**
     * Count the number of segments in a path
     */
    function numSegments(string $path, bool $trim = true): int
    {
        $path = $trim ? trim($path, '/') : $path;
        return count(explode('/', $path));
    }
}
