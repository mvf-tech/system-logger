<?php

namespace MVF\SystemLogger;

use MVF\SystemLogger\Reporters\Remotes\DataDog\EnvInterface;

class Env implements EnvInterface
{
    /**
     * Gets the environment variable or the default if none found.
     *
     * @param string          $name    Name of the variable
     * @param int|string|null $default Default value
     *
     * @return int|string
     */
    public function get(string $name, $default = null)
    {
        $var = getenv($name);
        if (is_numeric($var) === true) {
            return intval($var);
        }
        if (is_string($var) === true) {
            return $var;
        }

        return $default;
    }
}
