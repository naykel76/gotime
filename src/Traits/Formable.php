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
     * Safely transfers model attributes to matching form properties with
     * defensive type casting. Handles edge cases where database drivers
     * return unexpected types (e.g., numeric columns as strings).
     *
     * Type Handling:
     * - string: Converts null to empty string for form binding
     * - bool: Explicit cast to ensure boolean type
     * - int: Handles string numbers from DB, converts empty strings to null
     * - array: Wraps scalar values, preserves arrays, uses [] for empty
     * - untyped: Treats as string (null becomes '')
     * - other types: Assigned directly
     *
     * Only processes attributes that exist on the model. Form properties
     * without corresponding model attributes retain their default values.
     */
    protected function setFormProperties(Model $model): void
    {
        foreach ($model->getAttributes() as $property => $value) {
            if (property_exists($this, $property)) {
                // Get cast value, not raw DB value
                $value = $model->$property;

                $reflection = new \ReflectionProperty($this, $property);
                $type = $reflection->getType();

                if (! $type) {
                    $this->$property = $value ?? '';

                    continue;
                }

                $typeName = $type->getName();

                match ($typeName) {
                    'string' => $this->$property = $value ?? '',
                    'bool' => $this->$property = (bool) $value,
                    'int' => $this->$property = ($value === null || $value === '') ? null : (int) $value,
                    'array' => $this->$property = is_array($value) ? $value : (empty($value) ? [] : [$value]),
                    default => $this->$property = $value,
                };
            }
        }
    }
}
