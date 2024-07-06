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
     * https://naykel.com.au/gotime/traits/formable#content-editing
     * 
     * @var Model|null
     */
    public ?Model $editing;

    /**
     * Set form properties for a given model. 
     * 
     * This method iterates over the model's attributes and sets the form's
     * properties to the corresponding values if the property exists in the form
     * object.
     *
     * @param Model $model The model to get properties from.
     * @return void
     */
    protected function setFormProperties(Model $model): void
    {
        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value ?? '';
            }
        }
    }
}
