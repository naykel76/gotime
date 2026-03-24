<?php

namespace Naykel\Gotime\Components;

use Illuminate\View\Component;

class Filepond extends Component
{
    public function __construct(
        public string $type = '',
        public ?int $maxFileSize = null, // size in MB
        public array|string|null $accept = null,
    ) {
        $this->maxFileSize = $this->maxFileSize
            ?? (int) config('gotime.max_file_size', 300);
    }

    // public bool $multiple = false,
    // public ?bool $allowDrop = null,
    // public ?int $maxFiles = null,

    public function accepts(): ?array
    {
        if ($this->accept !== null) {
            if (is_array($this->accept)) {
                return $this->accept;
            }

            return array_values(array_filter(array_map('trim', explode(',', $this->accept))));
        }

        return match ($this->type) {
            'audio' => ['audio/*'],
            'image' => ['image/*'],
            'video' => ['video/*'],
            'pdf' => ['application/pdf', '.pdf'],
            'csv' => ['text/csv', '.csv'],
            'spreadsheet', 'excel' => ['.csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'text' => ['text/plain', '.txt'],
            'html' => ['text/html', '.html', '.htm'],
            'file', 'document', 'download' => [
                'application/pdf',
                '.pdf',
                '.doc',
                '.docx',
                '.xls',
                '.xlsx',
                '.csv',
                '.txt',
                '.zip',
            ],
            default => null,
        };
    }

    public function render()
    {
        return view('gotime::components.input.controls.filepond');
    }
}
