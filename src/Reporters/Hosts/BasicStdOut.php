<?php

namespace MVF\SystemLogger\Reporters\Hosts;

use MVF\SystemLogger\HostLogInterface;

class BasicStdOut implements HostLogInterface
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
        try {
            echo "${message}\n";
        } catch (\Exception $e) {
            return $e;
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
        try {
            echo "${message}\n";
        } catch (\Exception $e) {
            return $e;
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
        try {
            echo "${message}\n";
        } catch (\Exception $e) {
            return $e;
        }

        return null;
    }
}
