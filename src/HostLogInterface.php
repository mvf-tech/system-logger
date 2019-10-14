<?php

namespace MVF\SystemLogger;

interface HostLogInterface
{
    /**
     * Logs an info message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return \Exception|null
     */
    public function info($message);

    /**
     * Logs an warning message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return \Exception|null
     */
    public function warning($message);

    /**
     * Logs an error message to standard out.
     *
     * @param mixed $message Message to be logged
     *
     * @return \Exception|null
     */
    public function error($message);
}
