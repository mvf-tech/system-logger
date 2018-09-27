<?php

namespace MVF\SystemLogger;

use MVF\SystemLogger\Message\InputHandler;

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
        $exception = null;
        $hostLogs = [];
        $inputs = new InputHandler($data, $message);
        foreach ($loggers as $i => $logger) {
            if ($logger instanceof RemoteLogInterface) {
                $inputs->replaceValue($i, $logger->getValue());
                $e = $logger->send(array_merge(['info'], $inputs->getTags()));
                if ($e !== null) {
                    $exception = $e;
                }
            }
            if ($logger instanceof HostLogInterface) {
                $hostLogs[] = $logger;
            }
        }

        foreach ($hostLogs as $logger) {
            $e = $logger->info($inputs->getMessageWithValues());
            if ($e !== null) {
                $exception = $e;
            }
        }

        if ($exception !== null) {
            throw $exception;
        }
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
        $exception = null;
        $hostLogs = [];
        $inputs = new InputHandler($data, $message);
        foreach ($loggers as $i => $logger) {
            if ($logger instanceof RemoteLogInterface) {
                $inputs->replaceValue($i, $logger->getValue());
                $e = $logger->send(array_merge(['warning'], $inputs->getTags()));
                if ($e !== null) {
                    $exception = $e;
                }
            } elseif ($logger instanceof HostLogInterface) {
                $hostLogs[] = $logger;
            }
        }

        foreach ($hostLogs as $logger) {
            $e = $logger->warning($inputs->getMessageWithValues());
            if ($e !== null) {
                $exception = $e;
            }
        }

        if ($exception !== null) {
            throw $exception;
        }
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
        $exception = null;
        $hostLogs = [];
        $inputs = new InputHandler($data, $message);
        foreach ($loggers as $i => $logger) {
            if ($logger instanceof RemoteLogInterface) {
                $inputs->replaceValue($i, $logger->getValue());
                $e = $logger->send(array_merge(['error'], $inputs->getTags()));
                if ($e !== null) {
                    $exception = $e;
                }
            } elseif ($logger instanceof HostLogInterface) {
                $hostLogs[] = $logger;
            }
        }

        foreach ($hostLogs as $logger) {
            $e = $logger->error($inputs->getMessageWithValues());
            if ($e !== null) {
                $exception = $e;
            }
        }

        if ($exception !== null) {
            throw $exception;
        }
    }
}
