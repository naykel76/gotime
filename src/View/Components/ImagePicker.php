<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;

class ImagePicker extends Component
{

    public $for; // database_field
    public $imagePath; // database_field
    public $alt;

    public function __construct($for = 'image_path', $alt = null, $imagePath = null)
    {
        $this->for = $for;
        $this->alt = $alt;
        $this->imagePath = $imagePath;
    }

    public function render()
    {
        return view('gotime::components.image-picker');
    }
}
