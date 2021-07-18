<?php

/**
 * Redirect action after record saved based on form action
 * --------------------------------------------------------------------------
 * @param string $action request from form action=''
 * @param string $resource (name of resource e.g. pages, users, courses)
 * @param integer $id 
 */
function redirectTo($resource, $id)
{
    switch (request('action')) {
        case 'save_stay':
            // return back()->with('flash', 'Saved!');
            return redirect(route("$resource.edit", $id))->with('flash', 'Saved!');
            break;
        case 'save_close':
            return redirect(route("$resource.index"))->with('flash', 'Saved!');
            break;
        case 'save_new':
            return redirect(route("$resource.create"))->with('flash', 'Saved!');
            break;
        case 'save_preview':
            return redirect(route("$resource.show", $id))->with('flash', 'Saved!');
            break;
        case 'close':
            return redirect(route("$resource.index"));
            break;
    }
}

/**
 * - Uploads file and returns array of file data
 * - Keeps original file name (will override file if exists)
 * - method does not discriminate between file types (validate in request)
 * 
 * @param object $file (input name) (instance of file object. image, pdf, zip, etc ...)
 * @param string $path (storage path)
 * @param string $name optional parameter for user defined file name
 * @return array ['filePath' => $filePath, 'fileName' => $fileName]
 */
function storeOverrideFile($file, $path, $name = null, $disk = 'public')
{

    if ($name == null) {
        // get original file name
        $fileName = $file->getClientOriginalName();
    } else {
        // if name, concatenate name and get file extension
        $fileName = $name . '.' . $file->getClientOriginalExtension(); // set user defined name
    }

    // store file and return path
    $filePath = $file->storeAs($path, $fileName, $disk); 

    return ['filePath' => $filePath, 'fileName' => $fileName];
}

// /**
//  * Uploads file to nominated storage directory and removes old one if exists.
//  * --------------------------------------------------------------------------
//  *
//  * EXAMPLE USAGE:
//  *  if ($request->has('file')) {
//  *       $fileData = addRemoveFile($request->file, $this->storageDir, $oldPath);
//  *       $validatedData['file_path'] = $fileData['filePath'];
//  *   }
//  *
//  * @param object $file (instance of file object. image, video, pdf, zip, etc ...)
//  * @param string $path (storage path)
//  * @param string $newName optional parameter for user defined file name
//  * @return array ['filePath' => $filePath, 'fileName' => $fileName]
//  */
// function addRemoveFile($file, $path, $oldPath = null, $newName = null)
// {
//     if ($newName == null) {
//         $fileName = $file->getClientOriginalName(); // get original file name
//     } else {
//         $extension = '.' . $file->getClientOriginalExtension(); // get file extension
//         $fileName = $newName . $extension; // set user defined name
//     }

//     // if a file exists for this resource, delete it
//     $exists = Storage::delete($oldPath); // check if the file already exists in storage
//     $filePath = $file->storeAs($path, $fileName); // get path and store the file

//     return ['filePath' => $filePath, 'fileName' => $fileName];
// }





// /**
//  * get the highest value from a $collection of items then return the value + 5
//  * IMPORTANT: the collection must have the 'order' field
//  * ----------------------------------------------------------------------------
//  * @param mixed $collection
//  * @return int
//  */
// function addToEnd($collection)
// {
//     return $collection->max('order') + 5;
// }
