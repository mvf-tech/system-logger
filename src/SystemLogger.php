<?php

namespace MVF\SystemLogger;

use MVF\SystemLogger\Core\Log;

class SystemLogger implements SystemLoggerInterface
{
    /**
     * SystemLogger constructor.
     *
     * @param null|HostLogInterface $errorLogger The logger to be used for internal package errors
     */
    public function __construct($errorLogger = null)
    {
        // Do nothing.
    }

    /**
     * Sends an info log to the provided list of loggers.
     *
     * @param array                                         $data       The list of tags to be used by the loggers
     * @param string                                        $message    Message to be logged
     * @param array|(RemoteLogInterface|HostLogInterface)[] ...$loggers Implementations of the logging procedure
     *
     * @throws \Exception of the logger/s, if there were any
     */
    public function info(array $data, string $message, ...$loggers)
    {
        $log = new Log($data, $message);
        $log->handle('info', $loggers);
    }

    /**
     * Sends a warning log to the provided list of loggers.
     *
     * @param array                                         $data       The list of tags to be used by the loggers
     * @param string                                        $message    Message to be logged
     * @param array|(RemoteLogInterface|HostLogInterface)[] ...$loggers Implementations of the logging procedure
     *
     * @throws \Exception of the logger/s, if there were any
     */
    public function warning(array $data, string $message, ...$loggers)
    {
        $log = new Log($data, $message);
        $log->handle('warning', $loggers);
    }

    /**
     * Sends an error to the provided list of loggers.
     *
     * @param array                                         $data       The list of tags to be used by the loggers
     * @param string                                        $message    Message to be logged
     * @param array|(RemoteLogInterface|HostLogInterface)[] ...$loggers Implementations of the logging procedure
     *
     * @throws \Exception of the logger/s, if there were any
     */
    public function error(array $data, string $message, ...$loggers)
    {
        $log = new Log($data, $message);
        $log->handle('error', $loggers);
    }
}
