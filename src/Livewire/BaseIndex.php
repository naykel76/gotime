<?php

namespace Naykel\Gotime\Livewire;

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
        $this->afterConfigLoaded();
    }

    protected function afterConfigLoaded(): void {}

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

    public function render()
    {
        return $this->view([
            'title' => $this->title,
        ]);
    }
}
