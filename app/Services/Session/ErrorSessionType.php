<?php

namespace app\Services\Session;

class ErrorSessionType implements SessionTypeInterface
{

    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        return "ERROR";
    }
}