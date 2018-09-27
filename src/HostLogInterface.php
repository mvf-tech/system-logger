<?php

namespace MVF\SystemLogger;

interface HostLogInterface
{
    /**
     * Logs an info message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return bool
     */
    public function info($message);

    /**
     * Logs an warning message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return bool
     */
    public function warning($message);

    /**
     * Logs an error message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return bool
     */
    public function error($message);
}
