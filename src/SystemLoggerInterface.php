<?php

namespace MVF\SystemLogger;

interface SystemLoggerInterface
{
    /**
     * Sends an info log to the provided list of loggers.
     *
     * @param array                                         $data       The list of tags to be used by the loggers
     * @param string                                        $message    Message to be logged
     * @param array|(RemoteLogInterface|HostLogInterface)[] ...$loggers Implementations of the logging procedure
     */
    public function info(array $data, string $message, ...$loggers);

    /**
     * Sends a warning log to the provided list of loggers.
     *
     * @param array                                         $data       The list of tags to be used by the loggers
     * @param string                                        $message    Message to be logged
     * @param array|(RemoteLogInterface|HostLogInterface)[] ...$loggers Implementations of the logging procedure
     */
    public function warning(array $data, string $message, ...$loggers);

    /**
     * Sends an error to the provided list of loggers.
     *
     * @param array                                         $data       The list of tags to be used by the loggers
     * @param string                                        $message    Message to be logged
     * @param array|(RemoteLogInterface|HostLogInterface)[] ...$loggers Implementations of the logging procedure
     */
    public function error(array $data, string $message, ...$loggers);
}
