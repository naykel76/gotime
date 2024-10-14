<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;

trait Formable
{
    /**
     * Represents the model being edited or a new instance being created.
     *
     * This property only reflects the model's state when initially set or after
     * saving the form. It does not update to reflect changes made during form
     * editing.
     *
     * https://naykel.com.au/gotime/traits/formable#editing
     */
    public ?Model $editing;

    /**
     * Set form properties for a given model.
     *
     * This method iterates over the model's attributes and sets the form's
     * properties to the corresponding values if the property exists in the form
     * object.
     *
     * @param  Model  $model  The model to get properties from.
     */
    protected function setFormProperties(Model $model): void
    {

        // It is IMPORTANT to note that this method will only set values for
        // properties that have been set in the model. New models will not have
        // any properties set. The solution is initialize required properties
        // when creating a new `Model`.

        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value ?? '';
            }
        }
    }

    /**
     * Create a new instance of the model.
     *
     * This is required to create a new model instance when working on the same
     * page (modal). Do not remove from here!
     *
     * @param  array  $data  The data to be used to create the model instance.
     * @return Model The newly created model instance.
     */
    public function createNewModel(array $data = []): Model
    {
        $data = $this->initialData ?? $data ?? [];
        $model = $this->model::make($data);

        return $model;
    }
}
