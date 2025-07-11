<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;

// ------------------------------------------------------------------
// -- FILES AND FILESYSTEM --
// ------------------------------------------------------------------

if (! function_exists('getJsonFile')) {
    function getJsonFile(string $path, bool $returnAsArray = false): array|object
    {
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), $returnAsArray);
        }

        throw new FileNotFoundException("File does not exist at path {$path}.");
    }
}

if (! function_exists('getFile')) {
    function getFile(string $path): string|false
    {
        if (file_exists($path)) {
            return file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}.");
    }
}

if (! function_exists('fileExists')) {
    function fileExists(string $path): string|false
    {
        if (! file_exists($path)) {
            throw new FileNotFoundException("File does not exist at path {$path}.");
        }

        return true;
    }
}

// ------------------------------------------------------------------
// -- URL PATH CONVERSION HELPERS --
// ------------------------------------------------------------------

if (! function_exists('toPath')) {
    /**
     * Convert a dot notation string to a path, or sanitize an existing path.
     *
     * If the input string is in dot notation (e.g., 'folder.subfolder.item'),
     * this function will convert it to a path (e.g., 'folder/subfolder/item').
     *
     * If the input string is already a path, this function will sanitize it
     * by removing any leading slash.
     *
     * @param  string  $input  The input string in dot notation or path format.
     * @return string The sanitized path.
     */
    function toPath(string $input): string
    {
        return str_replace('.', '/', ltrim($input, '/'));
    }
}

if (! function_exists('toDot')) {
    /**
     * Convert url or path to dot notation
     */
    function toDot(string $input): string
    {
        return str_replace('/', '.', ltrim($input, '.'));
    }
}

if (! function_exists('numSegments')) {
    /**
     * Count the number of segments in a path
     */
    function numSegments(string $path, bool $trim = true): int
    {
        $path = $trim ? trim($path, '/') : $path;

        return count(explode('/', $path));
    }
}

if (! function_exists('dotLastSegment')) {
    /**
     * Explode dot notation and return the last segment
     */
    function dotLastSegment(string $item): string
    {
        $arr = explode('.', $item);

        return end($arr);
    }
}

// ------------------------------------------------------------------
// -- MATH HELPERS --
// ------------------------------------------------------------------

if (! function_exists('calcPercentage')) {
    function calcPercentage($total, $num): float
    {
        return $num / $total * 100;
    }
}

// ------------------------------------------------------------------
// -- MATH HELPERS --
// ------------------------------------------------------------------

if (! function_exists('getWord')) {
    function getWord(string $string, int $position = 0): ?string
    {
        $words = preg_split('/\s+/', trim($string));

        return $words[$position] ?? null;
    }
}
