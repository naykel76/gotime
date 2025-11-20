<?php

namespace Naykel\Gotime\Components;

use Illuminate\View\Component;

class Filepond extends Component
{
    public function __construct(
        public string $type = '',
        public int $maxFileSize = 5120, // size in KB
    ) {}

    // public bool $multiple = false,
    // public ?bool $allowDrop = null,
    // public ?int $maxFiles = null,

    public function accepts(): ?string
    {
        return match ($this->type) {
            'audio' => 'audio/*',
            'image' => 'image/*',
            'video' => 'video/*',
            'pdf' => '.pdf',
            'csv' => '.csv',
            'spreadsheet', 'excel' => '.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text' => 'text/plain',
            'html' => 'text/html',
            default => null,
        };
    }

    public function render()
    {
        return view('gotime::components.input.controls.filepond');
    }
}
