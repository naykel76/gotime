<?php

namespace Naykel\Gotime\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use Naykel\Gotime\Traits\HasConfig;
use Naykel\Gotime\Traits\WithFormActions;

abstract class BaseForm extends Component
{
    use HasConfig, WithFormActions;

    public string $title = '';
    public string $routePrefix = '';

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

    public function formTitle(): string
    {
        if ($this->isNewModel()) {
            if (property_exists($this, 'createTitle') && filled($this->createTitle)) {
                return (string) $this->createTitle;
            }

            return "{$this->titlePrefix()} {$this->resolveResourceTitle()}";
        }

        if (property_exists($this, 'editTitlePrefix') && filled($this->editTitlePrefix)) {
            $editPrefix = trim((string) $this->editTitlePrefix);

            if ($editPrefix === 'Edit' && filled($this->form->title ?? null)) {
                return "{$editPrefix} - {$this->form->title}";
            }

            return $editPrefix;
        }

        return "{$this->titlePrefix()} {$this->resolveResourceTitle()}";
    }

    protected function resolveResourceTitle(): string
    {
        return (string) Str::of($this->configKey())
            ->replace(['.', '_', '-'], ' ')
            ->headline();
    }

    public function render()
    {
        return $this->view([
            'title' => $this->formTitle(),
        ]);
    }
}
