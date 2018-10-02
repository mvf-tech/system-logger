<?php

namespace MVF\SystemLogger;

use MVF\SystemLogger\Message\InputHandler;
use function Functional\first;
use function Functional\map;
use function Functional\select;

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
        $inputs = new InputHandler($data, $message);
        $this->performLog($inputs, 'info', $loggers);
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
        $inputs = new InputHandler($data, $message);
        $this->performLog($inputs, 'warning', $loggers);
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
        $inputs = new InputHandler($data, $message);
        $this->performLog($inputs, 'error', $loggers);
    }

    /**
     * Performs the logging operations.
     *
     * @param InputHandler                                  $inputs   An object to handle message placeholders
     * @param string                                        $severity Define how severe the log is
     * @param array|(RemoteLogInterface|HostLogInterface)[] $loggers  Implementations of the logging procedure
     *
     * @throws \Exception of the logger/s, if there were any
     */
    private function performLog(InputHandler $inputs, string $severity, array $loggers)
    {
        $remoteLogs = select($loggers, $this->remote());
        $hostLogs = select($loggers, $this->host());
        $remoteExceptions = map($remoteLogs, $this->performRemoteLog($inputs, $severity));
        $hostExceptions = map($hostLogs, $this->performHostLog($inputs, $severity));

        $exception = first(array_merge($remoteExceptions, $hostExceptions));
        if ($exception !== null) {
            throw $exception;
        }
    }

    /**
     * Returns a function that is true if input parameter is of type RemoteLogInterface.
     *
     * @return callable
     */
    private function remote(): callable
    {
        return function ($logger) {
            return $logger instanceof RemoteLogInterface;
        };
    }

    /**
     * Returns a function that is true if input parameter is of type HostLogInterface.
     *
     * @return callable
     */
    private function host(): callable
    {
        return function ($logger) {
            return $logger instanceof HostLogInterface;
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
    private function performRemoteLog(InputHandler $inputs, string $severity): callable
    {
        return function (RemoteLogInterface $remote, $i) use ($inputs, $severity) {
            $inputs->replaceValue($i, $remote->getValue());

            return $remote->send(array_merge([$severity], $inputs->getTags()));
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
    private function performHostLog(InputHandler $inputs, string $severity): callable
    {
        return function (HostLogInterface $host) use ($inputs, $severity) {
            return $host->{$severity}($inputs->getMessageWithValues());
        };
    }
}
