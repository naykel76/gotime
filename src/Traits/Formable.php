<?php

namespace Naykel\Gotime\Traits;

use Illuminate\Database\Eloquent\Model;

trait Formable
{
    /**
     * The model being edited or created.
     *
     * Only reflects the model's state when initially set or after saving.
     * Does not auto-sync with form changes.
     *
     * https://naykel.com.au/gotime/traits/formable#editing
     */
    public ?Model $editing;

    /**
     * Set form properties from model attributes.
     *
     * Iterates over the model's attributes and sets matching form properties
     * if they exist on the form object. Properties are type-cast appropriately
     * based on their declared type hints.
     *
     * This method only processes attributes that exist on the model, so form
     * properties without corresponding model attributes will retain their
     * default values.
     */
    protected function setFormProperties(Model $model): void
    {
        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                // Use PHP Reflection to inspect the property's declared type.
                // This is necessary because PHP doesn't provide a direct way to
                // access a property's type hint at runtime without reflection.
                $reflection = new \ReflectionProperty($this, $property);
                $type = $reflection->getType();

                // Handle type casting for form properties:
                // - For int: avoid casting '' to 0, which can be misleading.
                // - For array: ensure only arrays are assigned, wrap non-empty
                //   scalars in an array, use [] for empty values.
                if ($type && $type->getName() === 'int') {
                    $this->$property = ($value === null || $value === '') ? null : (int) $value;
                } elseif ($type && $type->getName() === 'array') {
                    $this->$property = is_array($value) ? $value : (empty($value) ? [] : [$value]);
                } else {
                    $this->$property = $value ?? '';
                }
            }
        }
    }
}
