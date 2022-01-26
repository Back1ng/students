<?php

namespace app\Services\Session;

class SuccessSessionType implements SessionTypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        return "SUCCESS";
    }
}