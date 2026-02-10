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

    abstract protected function configKey(): string;

    // CHECK: This method is not currently called anywhere. I believe this
    // intention is for it to be called in the mount method of the child class.
    // What is the benefit of this approach vs just putting the code in the
    // mount method of the child class? 
    public function afterMount(Model $model)
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
}
