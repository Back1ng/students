<?php

namespace app\Services\Cookie;

class CookieManager
{
    private array $preset = [
        'default' => ['ID_AUTH_STUDENT', 'STUDENT_NAME', 'STUDENT_SURNAME'],
        'add' => ['TOKEN'],
    ];

    /**
     * Set cookies by ready preset.
     *
     * @param string $preset Available presets:
     * `default` - ID_AUTH_STUDENT, STUDENT_NAME, STUDENT_SURNAME
     * `update`  - TOKEN
     *
     * @param array $cookieNames Must be in order, like in preset
     *
     * @param int $time
     *
     * @param bool $withoutDefault
     *
     * @return void
     */
    public function set(string $preset, array $cookieNames, int $time, bool $withoutDefault = false)
    {
        $presetNames = [];
        if (! $withoutDefault && $preset !== 'default') {
            $presetNames = $this->getPreset('default');
        }

        foreach ($this->getPreset($preset) as $cookieName) {
            $presetNames[] = $cookieName;
        }

        foreach ($presetNames as $key => $cookieName) {
            \setcookie($cookieName, $cookieNames[$key], $time);
        }
    }

    /**
     * Return value of requested cookie
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $_COOKIE[$name];
    }

    private function getPreset(string $type): array
    {
        return $this->preset[$type];
    }
}