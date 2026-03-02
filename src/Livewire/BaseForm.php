<?php

namespace Naykel\Gotime\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Naykel\Gotime\Traits\HasConfig;
use Naykel\Gotime\Traits\WithFormActions;

abstract class BaseForm extends Component
{
    use HasConfig, WithFormActions;

    public string $title = '';
    public string $routePrefix = '';
    public string $createTitle = 'Create';
    public string $editTitlePrefix = 'Edit';
    public ?string $titleField = 'title';

    abstract protected function configKey(): string;

    /**
     * Child components call this from their mount() since Livewire doesn't
     * stack mount methods. It centralises form setup in the base class.
     */
    public function initForm(Model $model): void
    {
        $this->loadFormConfig($this->configKey());
        $model = $model->exists ? $model : new $this->modelClass;
        $this->form->init($model);
    }

    public function render()
    {
        return $this->view([
            'title' => $this->title,
        ]);
    }

    public function formTitle(): string
    {
        if (! $this->isEditing()) {
            return $this->createTitle;
        }

        $fieldValue = $this->editingLabel();

        if (blank($fieldValue)) {
            return $this->editTitlePrefix;
        }

        return "{$this->editTitlePrefix} - {$fieldValue}";
    }

    protected function isEditing(): bool
    {
        return isset($this->form, $this->form->editing) && filled($this->form->editing?->id);
    }

    protected function editingLabel(): ?string
    {
        if (! $this->titleField) {
            return null;
        }

        return $this->form->{$this->titleField} ?? null;
    }
}
