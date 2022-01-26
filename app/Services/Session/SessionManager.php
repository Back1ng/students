<?php

namespace app\Services\Session;

class SessionManager
{
    /**
     * Add message to session
     *
     * @param SessionTypeInterface $sessionType
     * @param string $message
     * @return bool result of adding message to session
     */
    public static function add(SessionTypeInterface $sessionType, string $message): bool
    {
        $_SESSION[$sessionType->get()] = $message;

        return self::exist($sessionType);
    }

    public static function exist(SessionTypeInterface $sessionType): bool
    {
        return isset($_SESSION[$sessionType->get()]) && !empty($_SESSION[$sessionType->get()]);
    }
}