<?php

namespace MVF\SystemLogger\Core;

use MVF\SystemLogger\HostLogInterface;
use MVF\SystemLogger\Message\InputHandler;

class Host
{
    /**
     * Returns a function that is true if input parameter is of type HostLogInterface.
     *
     * @return callable
     */
    public static function loggers(): callable
    {
        return function ($logger) {
            return $logger instanceof HostLogInterface;
        };
    }

    /**
     * Performs a log using the host interface.
     *
     * @param InputHandler $inputs   An object to handle message placeholders
     * @param string       $severity Define how severe the log is (one of info, warning, error)
     *
     * @return callable
     */
    public static function log(InputHandler $inputs, string $severity): callable
    {
        return function (HostLogInterface $host) use ($inputs, $severity) {
            return $host->{$severity}($inputs->getMessageWithValues());
        };
    }
}
