<?php

namespace MVF\SystemLogger\Reporters\Remotes\DataDog\Methods;

use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\DataDog;

class Time extends DataDog implements RemoteLogInterface
{
    private $exception;

    /**
     * Time constructor.
     *
     * @param string         $suffix String that is appended to the DataDog metric name
     * @param callable|float $time   Either a number that should be logged to DataDog
     *                               or a closure that will be timed internally
     *
     * @deprecated 4.0.0
     */
    public function __construct(string $suffix, $time)
    {
        parent::__construct();

        if (is_callable($time) === true) {
            try {
                $timeStart = microtime(true);
                $time();
                $timeEnd = microtime(true);
                $this->value = ($timeEnd - $timeStart);
            } catch (\Exception $e) {
                $this->exception = $e;
            }
        } elseif (is_numeric($time) === true) {
            $this->value = $time;
        } else {
            $this->exception = new \InvalidArgumentException('Time attribute must be a float or callable');
        }

        if (empty($suffix) === false) {
            $this->suffix = ".${suffix}";
        }
    }

    /**
     * Sends the message to the DataDog.
     *
     * @param array $tags The list of tags to be sent
     *
     * @return \Exception|null
     */
    public function send(array $tags)
    {
        if (isset($this->exception) === true) {
            return $this->exception;
        }

        try {
            $this->client->timing(
                $this->project . '.' . $this->environment . $this->suffix,
                $this->value,
                $this->addJustKeys($tags, ['time'])
            );
        } catch (\Exception $e) {
            $this->client->histogram(
                $this->project . '.' . $this->environment . '.datadog',
                1,
                1,
                [
                    'error',
                    'message',
                    'message' => $e->getMessage(),
                ]
            );

            return $e;
        }

        return null;
    }
}
