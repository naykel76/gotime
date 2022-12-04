<?php

// ------------------------------------------------------------------
// -- GENERAL HELPERS --
// ------------------------------------------------------------------

if (!function_exists('addToEnd')) {
    /**
     * get the highest value of a field in a $collection and increment the value
     * ----------------------------------------------------------------------------
     * @param mixed $collection
     * @param mixed $value
     * @param string $field
     * @param int $increment
     * @return mixed
     */
    function addToEnd($collection, $field = 'sort_order', $increment = 5)
    {
        return $collection->max($field) + $increment;
    }
}

if (!function_exists('dotLastItem')) {
    /**
     * explode dot notation and return the last item
     * ----------------------------------------------------------------------------
     */
    function dotLastItem($item)
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

//
//
//
// /**
//  * On form submit, redirect based on form action using resource id
//  * --------------------------------------------------------------------------
//  * @param string $action request from form action=''
//  * @param string $routeName (full route name e.g. admin.courses, courses)
//  * @param integer $id
//  */

// if (!function_exists('redirectById')) {
//     function redirectById($routeName, $id)
//     {
//         // dd($routeName);
//         switch (request('action')) {
//             case 'save_stay':
//                 // dd($routeName . ".edit" . $id);
//                 return redirect(route("$routeName.edit", $id))->with('flash', 'Saved!');
//                 break;
//             case 'save_close':
//                 return redirect(route("$routeName.index"))->with('flash', 'Saved!');
//                 break;
//             case 'save_new':
//                 return redirect(route("$routeName.create"))->with('flash', 'Saved!');
//                 break;
//             case 'save_preview':
//                 return redirect(route("$routeName.show", $id))->with('flash', 'Saved!');
//                 break;
//             case 'close':
//                 return redirect(route("$routeName.index"));
//                 break;
//         }
//     }
// }
//
//
//
//
//
//
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
//
//
//
//
//
//
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

if (!function_exists('fetchJsonFile')) {
    /**
     * fetch json file contents (as object or array)
     * --------------------------------------------------------------
     * @param mixed $path
     * @param bool $returnAsArray
     * @return mixed
     */
    function fetchJsonFile($path, $returnAsArray = false)
    {
        $file = resource_path($path);
        return json_decode(file_get_contents($file), $returnAsArray);
    }
}


/**
 * Convert dot notation and remove leading forward slash
 * @param string $input
 * @return string
 */
function sanitizeUrlPath(string $input): string
{
    return str_replace('.', '/', ltrim($input, '/'));
}
