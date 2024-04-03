<?php

namespace Naykel\Gotime\Traits;

trait Draggable
{

    /**
     * Updates the sort order of the models.
     *
     * This method takes an array of ordered IDs, finds each model by its ID,
     * and updates its 'sort_order' attribute. After updating the models, it
     * reloads the items and dispatches a 'notify' event.
     *
     * @param  array  $orderedIds  An array of arrays, each containing a 'value'
     * key (the model ID) and an 'order' key (the new sort order).
     * @throws \Exception If the $model property is not set. @return void
     */
    public function updateSortOrder($orderedIds)
    {
        if (!isset($this->model)) {
            throw new \Exception('Property $model is not set in ' . __CLASS__ . ". ---- Eg. protected \$model = User::class;");
        }

        foreach ($orderedIds as $item) {
            $this->model::find($item['value'])->update(['sort_order' => $item['order']]);
        }

        $this->loadItems();
        $this->dispatch('notify', 'Item order updated');
    }
}
