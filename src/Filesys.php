<?php

namespace Naykel\Gotime;

class Filesys

{
    /**
     * - Uploads file to storage and returns array of file data
     * - Keeps original file name (will override file if exists)
     * - method does not discriminate between file types (validate in request)
     * ------------------------------------------------------------------------
     * @param object $file (file object instance. image, pdf, zip, etc ...)
     * @param string $path (storage path)
     * @param string $name optional parameter for user defined file name
     * @return array ['filepath' => $filepath, 'filename' => $filename]
     */
    public function addOverrideFile($file, $path, $name = null, $disk = 'public')
    {

        if ($name == null) {
            // get original file name
            $filename = $file->getClientOriginalName();
        } else {
            // if name, concatenate name and get file extension
            $filename = $name . '.' . $file->getClientOriginalExtension(); // set user defined name
        }

        // upload file to storage and return path
        $filepath = $file->storeAs($path, $filename, $disk);

        return ['filepath' => $filepath, 'filename' => $filename];
    }
}
