<?php

namespace MVF\SystemLogger\Core;

use MVF\SystemLogger\Message\InputHandler;
use function Functional\first;
use function Functional\map;
use function Functional\select;

class Log
{
    /**
     * Provides string placeholder functions.
     *
     * @var InputHandler
     */
    private $inputs;

    /**
     * Logger constructor.
     *
     * @param array  $data    The list of tags to be used by the loggers
     * @param string $message Message to be logged
     */
    public function __construct(array $data, string $message)
    {
        $this->inputs = new InputHandler($data, $message);
    }

    /**
     * Performs the logging operations.
     *
     * @param string                                        $severity Define how severe the log is
     * @param array|(RemoteLogInterface|HostLogInterface)[] $loggers  Implementations of the logging procedure
     *
     * @throws \Exception of the logger/s, if there were any
     */
    public function handle(string $severity, array $loggers)
    {
        $exceptions = array_merge(
            $this->log($loggers, Remote::loggers(), Remote::log($this->inputs, $severity)),
            $this->log($loggers, Host::loggers(), Host::log($this->inputs, $severity))
        );

        $exception = first($exceptions);
        if ($exception !== null) {
            throw $exception;
        }
    }

    private function log(array $allLoggers, callable $filter, callable $handler): array
    {
        $loggers = select($allLoggers, $filter);

        return map($loggers, $handler);
    }
}
