<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\View\Component;

class Markdown extends Component
{
    protected string $file;

    public function __construct(
        protected string $path,            // resource/views path excluding extension
        protected string $fullPath = ''    // full path excluding extension
    ) {
        if (!empty($fullPath)) {
            $this->file = getFile($this->fullPath . '.md');
        } else {
            $this->file = getFile($this->path . '.md');
            // $this->file = getFile(resource_path('views/' . $this->path . '.md'));
        }

    }
    
    public function render()
    {
        return view('gotime::components.markdown')->with(['file' => $this->file]);
    }
}
