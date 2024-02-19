<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;

trait Formable
{
    /**
     * @var Model The form object model currently being edited.
     */
    public Model $editing;


    /**
     * Set form properties from a given model.
     *
     * @param Model $model The model to get properties from.
     * @return void
     */
    protected function setFormProperties(Model $model): void
    {
        /**
         * This loop iterates over each attribute of the model. For each
         * attribute, it checks if a property with the same name exists in the
         * current object. If such a property exists, it assigns the value of
         * the attribute to the property. If the attribute's value is null, it
         * assigns an empty string to the property.
         */

        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value ?? '';
            }
        }
    }

    /**
     * Get the model instance that is currently being edited.
     *
     * @return Model The model instance being edited.
     */
    public function getEditingModel(): Model
    {
        return $this->editing;
    }
}
