<?php

namespace Naykel\Gotime\DTO;

class FileInfo
{
    public function __construct(public string $directory, public string $name, public string $disk) {}

    /**
     * Get the full path of the file excluding the disk.
     * ```
     * Example: 'images/2021/01/01/1234567890.jpg'
     * ```
     *
     * @return string The full path of the file excluding the disk.
     */
    public function path(): string
    {
        if ($this->directory === '') {
            return $this->name;
        }

        return $this->directory . '/' . $this->name;
    }

    /**
     * Get the full path of the file including the disk.
     * ```
     * Example: 'public/images/2021/01/01/1234567890.jpg'
     * ```
     *
     * @return string The full path of the file including the disk.
     */
    public function fullPathIncludingDisk(): string
    {
        return $this->disk . '/' . $this->directory . '/' . $this->name;
    }
}
