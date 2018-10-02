<?php

namespace MVF\SystemLogger\Core;

use MVF\SystemLogger\Message\InputHandler;
use MVF\SystemLogger\RemoteLogInterface;

class Remote
{
    /**
     * Returns a function that is true if input parameter is of type RemoteLogInterface.
     *
     * @return callable
     */
    public static function loggers(): callable
    {
        return function ($logger) {
            return $logger instanceof RemoteLogInterface;
        };
    }

    /**
     * Performs a log using the remote interface.
     *
     * @param InputHandler $inputs   An object to handle message placeholders
     * @param string       $severity Define how severe the log is (one of info, warning, error)
     *
     * @return callable
     */
    public static function log(InputHandler $inputs, string $severity): callable
    {
        return function (RemoteLogInterface $remote, $i) use ($inputs, $severity) {
            $inputs->replaceValue($i, $remote->getValue());

            return $remote->send(array_merge([$severity], $inputs->getTags()));
        };
    }
}
