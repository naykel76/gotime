<?php

namespace Naykel\Gotime\Traits;


trait WithLivewireHelpers
{
    //////////////////////////////////////////////////////////////////
    //     This trait is tightly coupled to the Formable trait      //
    //      the editing property must be $this->form->editing       //
    //////////////////////////////////////////////////////////////////

    public function save(string $action = null): void
    {
        $model = $this->form->save();

        // no need to redirect, just notify
        if (!$this->isNewModel() && $action == 'save_edit') {
            $this->dispatch('notify', 'Saved successfully!');
            return;
        }

        if ($action) {
            $this->handleRedirect($this->routePrefix, $action, $model->id);
        }

        $this->dispatch('notify', 'Saved successfully!');
    }

    /**
     * Determines if the model being edited is new (i.e., not yet saved in the database).
     *
     * @return bool Returns true if the model is new (without an ID), false otherwise.
     */
    public function isNewModel(): bool
    {
        return is_null($this->form->editing->id);
    }

    /**
     * Handles redirection based on the provided action.
     *
     * @param string $routePrefix The prefix for the route.
     * @param string $action The action to be performed 'save_close', 'delete_close' ...
     * @param int $id The optional ID for routes that require it.
     * @throws \Exception Throws an exception if an invalid action is provided.
     */
    function handleRedirect(string $routePrefix, string $action, int $id = null)
    {
        return match ($action) {
            'save_close', 'delete_close' => redirect(route("$this->routePrefix.index")),
            'save_new' => redirect(route("$routePrefix.create")),
            'save_edit', 'save_stay' => redirect(route("$routePrefix.edit", $id)),
            default => throw new \Exception("Invalid action: $action"),
        };
    }
}
