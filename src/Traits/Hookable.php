<?php

namespace Naykel\Gotime\Traits;

trait Hookable
{
    /**
     * This method is a hook that is called before validating the form data.
     * It can be used to perform additional tasks or manipulate the data before it gets validated.
     *
     * @return void
     */
    protected function beforeValidateHook(): void
    {
    }

    /**
     * This method is a hook that is called before persisting data to the database.
     * It can be used to perform additional tasks or manipulate the data before it gets saved.
     *
     * @return void
     */
    protected function beforePersistHook(): void
    {
    }

    /**
     * This method is a hook that is called after persisting data to the database.
     * It can be used to perform additional tasks or cleanup after the data has been saved.
     *
     * @return void
     */
    protected function afterPersistHook(): void
    {
    }
}
