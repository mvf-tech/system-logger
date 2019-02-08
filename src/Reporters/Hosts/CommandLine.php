<?php

namespace MVF\SystemLogger\Reporters\Hosts;

use MVF\SystemLogger\HostLogInterface;

class CommandLine implements HostLogInterface
{
    /**
     * Logs an info message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return \Exception|null
     */
    public function info($message)
    {
        if (fwrite(STDOUT, $message . PHP_EOL) === false) {
            return new \Exception('Failed to write to stdout file', 1);
        }

        return null;
    }

    /**
     * Logs an warning message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return \Exception|null
     */
    public function warning($message)
    {
        if (fwrite(STDOUT, $message . PHP_EOL) === false) {
            return new \Exception('Failed to write to stdout file', 1);
        }

        return null;
    }

    /**
     * Logs an error message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return \Exception|null
     */
    public function error($message)
    {
        if (fwrite(STDERR, $message . PHP_EOL) === false) {
            return new \Exception('Failed to write to stderr file', 1);
        }

        return null;
    }
}
