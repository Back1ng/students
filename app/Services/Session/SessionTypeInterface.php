<?php

namespace app\Services\Session;

interface SessionTypeInterface
{

    /**
     * Return string of type, i.e. "ERROR", "SUCCESS".
     */
    public function get(): string;
}