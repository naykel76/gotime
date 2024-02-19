<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;

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

// ------------------------------------------------------------------
// -- FILES AND FILESYSTEM --
// ------------------------------------------------------------------

if (!function_exists('getJsonFile')) {
    /**
     * Get json file contents
     * --------------------------------------------------------------
     */
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
// -- GENERAL HELPERS --
// ------------------------------------------------------------------


if (!function_exists('handleRedirect')) {
    /**
     * Handles redirection based on the provided action.
     *
     * @param string $routePrefix The prefix of the route to redirect to.
     * @param string $action The action to perform.
     * @param mixed $id The ID of the resource (optional).
     *
     * @return \Illuminate\Http\RedirectResponse A redirect response.
     */
    function handleRedirect(string $routePrefix, string $action, $id = null)
    {
        return match ($action) {
            'save_close', 'delete_close' => redirect(route($routePrefix . '.index')),
            'save_new' => redirect(route($routePrefix . '.create')),
            'save_stay' => redirect(route("$routePrefix.edit", $id)),
            default => throw new Exception("Invalid action: $action"),
        };
    }
}

// ------------------------------------------------------------------
// -- GENERAL HELPERS --
// ------------------------------------------------------------------

if (!function_exists('addToEnd')) {
    /**
     * This function finds the maximum value in a collection, increments it
     * by a specified amount, and returns the result.
     *
     * @param Collection $collection The collection to search.
     * @param string $field The field to find the maximum value of. Default is * 'sort_order'.
     * @param int $increment The amount to increment the maximum * value by. Default is 5.
     *
     * @return int The incremented maximum value.
     */
    function addToEnd($collection, $field = 'sort_order', $increment = 5)
    {
        return $collection->max($field) + $increment;
    }
}

if (!function_exists('dotLastSegment')) {
    /**
     * explode dot notation and return the last item
     * ----------------------------------------------------------------------------
     */
    function dotLastSegment($item)
    {
        $arr = explode(".", $item);
        return end($arr);
    }
}

// ------------------------------------------------------------------
// -- ROUTES AND REDIRECTS --
// ------------------------------------------------------------------
if (!function_exists('handleRedirect')) {
    /**
     *
     * @param mixed $routeName without the action
     * @param mixed $redirectAction case or action description
     * @param int|string|null $param
     * @return mixed
     */


    function handleRedirect(string $routeName, string $redirectAction, int|string $param = null)
    {
        switch ($redirectAction) {
            case 'save_stay':
                // this feels a bit hacky, but if there is not slug field or it is null it should work fine
                return redirect(route("$routeName.edit", $param));
                break;
            case 'save_close':
                return redirect(route("$routeName.index"));
                break;
            case 'save_new':
                return redirect(route("$routeName.create"));
                break;
            case 'delete_close':
                return redirect(route("$routeName.index"));
                break;
        }
    }
}

// ------------------------------------------------------------------
// -- FILES AND FILESYSTEM --
// ------------------------------------------------------------------

/**
 * Get filename with extension from a directory
 * @param mixed $dir
 * @param bool $fullPath (or only file name)
 *
 * @return array
 */

if (!function_exists('getFileNames')) {
    function getFileNames($dir, $fullPath = false): array
    {
        $filenames = [];

        foreach (\File::files(base_path($dir)) as $file) {
            $filenames[] = pathinfo($file, PATHINFO_BASENAME);
        }

        return $filenames;
    }
}



//
//
// IN REVIEW
//
//



/**
 * Uploads file to nominated storage directory and removes old one if exists.
 * --------------------------------------------------------------------------
 *
 * @param object $file (instance of file object. image, video, pdf, zip, etc ...)
 * @param string $path (storage path)
 * @param string $newName optional parameter for user defined file name
 * @return array ['filePath' => $filePath, 'fileName' => $fileName]
 */

if (!function_exists('addRemoveFile')) {
    function addRemoveFile($file, $path, $oldPath = null, $newName = null)
    {
        if ($newName == null) {
            $fileName = $file->getClientOriginalName(); // get original file name
        } else {
            $extension = '.' . $file->getClientOriginalExtension(); // get file extension
            $fileName = $newName . $extension; // set user defined name
        }

        // if a file exists for this resource, delete it
        $exists = Storage::delete($oldPath); // check if the file already exists in storage
        $filePath = $file->storeAs($path, $fileName); // get path and store the file

        return ['filePath' => $filePath, 'fileName' => $fileName];
    }
}






if (!function_exists('calcPercentage')) {
    function calcPercentage($total, $num)
    {
        return $num / $total * 100;
    }
}

if (!function_exists('dollarsToCents')) {
    /**
     * Convert dollars (float) to cents (int)
     * @param float $value
     * @return int
     */
    function dollarsToCents(float $value): int
    {
        return round($value * 100);
    }
}

if (!function_exists('centsToDollars')) {
    /**
     * Convert cents (int) to dollars (float)
     * @param int $value
     * @return float
     */
    function centsToDollars(int $value): float
    {
        return number_format(($value / 100), 2);
    }
}
