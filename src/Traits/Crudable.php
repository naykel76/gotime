<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;

trait Crudable
{

    /**
     * Validates the form data and persists the model.
     *
     * If the model already exists, it is updated with the validated data.
     * If the model does not exist, a new model is created with the validated data.
     *
     * @return Model The model that was updated or created. This is the same model as $this->editing.
     */
    public function save(): Model
    {
        $validatedData = $this->validate();

        method_exists($this, 'beforePersist') ? $this->beforePersist($validatedData) : null;

        $this->editing = $this->editing::updateOrCreate(['id' => $this->editing->id], $validatedData);

        return $this->editing;
    }
}
