<?php

namespace MVF\SystemLogger\Reporters\Remotes\DataDog;

interface EnvInterface
{
    /**
     * Gets the environment variable or the default if none found.
     *
     * @param string          $name    Name of the variable
     * @param int|string|null $default Default value
     *
     * @return int|string
     */
    public function get(string $name, $default = null);
}
