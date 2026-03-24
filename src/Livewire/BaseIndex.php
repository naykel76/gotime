<?php

namespace Naykel\Gotime\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Naykel\Gotime\Traits\HasConfig;

abstract class BaseIndex extends Component
{
    use HasConfig, WithPagination;

    public string $routePrefix = '';
    public string $title = '';
    public int $perPage = 16;
    public array $searchableFields = [];
    public array $filterableFields = [];
    public ?string $sortColumn = null;
    public string $sortDirection = 'asc';

    abstract protected function configKey(): string;

    public function mount(): void
    {
        $this->loadIndexConfig($this->configKey());
        $this->title = $this->resolveTitle();
        $this->afterConfigLoaded();
    }

    protected function afterConfigLoaded(): void {}

    protected function resolveTitle(): string
    {
        if (filled($this->title)) {
            return $this->title;
        }

        return (string) Str::of($this->configKey())
            ->replace(['.', '_', '-'], ' ')
            ->headline();
    }

    #[Computed]
    public function items()
    {
        return $this->buildQuery()->paginate($this->perPage);
    }

    protected function buildQuery()
    {
        $query = $this->modelClass::query();

        return $this->query($query);
    }

    protected function query($query)
    {
        return $query;
    }

    // public function render()
    // {
    //     return $this->view();
    // }
}
