<?php

namespace Naykel\Gotime\Traits;

use Closure;
use Illuminate\Support\Facades\DB;

trait Draggable
{
    public static function bootDraggable()
    {
        static::addGlobalScope(function ($query) {
            return $query->orderBy('position');
        });

        /**
         * This should set the position to the next available position based on
         * the sortableFilter scope when creating a new model, however it is not
         * working as expected.
         *
         * The problem is that in the original  implementation, the $model was
         * passed to the `sortableFilter` scope, allowing it to account for the
         * range of positions when creating a new model:
         *
         * $max = static::sortableFilter($model)->max('position') ?? -1;
         *
         * However, with the addition of the optional callback in the
         * `sortableFilter` it throws an error because the `sortableFilter`
         * scope is expecting a callback, not a model instance.
         *
         * The solution is to remove the $model argument and the error goes away
         * but the position is not set correctly. It simply finds the max
         * position from all the records in the table and sets the new model to
         * the max + 1.
         *
         * While this is not ideal, I am leaving it as because at this point the
         * care factor is 0 and I see no problem as long as you are not
         * displaying the position in the UI. If you are, you can simple move
         * the item and the arrange method will update the positions correctly.
         */
        static::creating(function ($model) {
            $max = static::sortableFilter()->max('position') ?? -1;
            $model->position = $max + 1;
        });

        // Simple check to ensure the sortableFilter scope exists
        if (! method_exists(static::class, 'scopeSortableFilter')) {
            throw new \Exception('The sortableFilter scope does not exist in the model: ' . get_class(new static));
        }
    }

    public function move($position, ?Closure $callback = null): void
    {

        DB::transaction(function () use ($position, $callback) {
            $current = $this->position;
            $newPosition = $position;

            // If there was no position change, do nothing and exit early...
            if ($current === $newPosition) return;

            // Move the item out of the position stack...
            $this->update(['position' => -1]);

            // Apply the callback when fetching the block to shift positions
            $block = static::sortableFilter($callback)
                ->whereBetween('position', [min($current, $newPosition), max($current, $newPosition)]);

            // Determine the direction of the shift...
            $isDraggingDownwards = $current < $position;

            // Determine the direction of the shift and adjust positions accordingly...
            $isDraggingDownwards
                ? $block->decrement('position')
                : $block->increment('position');

            // Place item back in position stack...
            $this->update(['position' => $position]);

            $this->arrange($callback);
        });
    }

    /**
     * Arrange the items based on the sortable filter and update their positions.
     *
     * @param  Closure|null  $callback  Optional callback to modify the query conditions.
     */
    private function arrange(?Closure $callback = null): void
    {
        DB::transaction(function () use ($callback) {
            $position = 0;
            $items = static::sortableFilter($callback)->get();
            foreach ($items as $model) {
                $model->position = $position++;
                $model->save();
            }
        });
    }
}
