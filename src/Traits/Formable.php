<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;

trait Formable
{
    /**
     * The persisted model that is being edited.
     * 
     * https://naykel.com.au/gotime/traits/formable#content-editing
     * 
     * @var Model|null
     */
    public ?Model $editing;

    /**
     * The editing model is the persisted model that is being edited. It will
     * only reflect the model state when it is first set, or after the form has
     * been saved.
     * 
     * It will not reflect the model state after the form has been edited.
     * 
     * So what is the point of this method? Without model binding, the editing
     * attribute is not that useful. However, it is good for checking existence
     * and using it to set the form properties.
     * 
     * @return Model|null 
     */
    protected function getEditingModel(): ?Model
    {
        return $this->editing;
    }

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
