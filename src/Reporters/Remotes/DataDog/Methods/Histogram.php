<?php

namespace MVF\SystemLogger\Reporters\Remotes\DataDog\Methods;

use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\DataDog;

class Histogram extends DataDog implements RemoteLogInterface
{
    private $sampleRate;

    /**
     * Histogram constructor.
     *
     * @param string $suffix     String that is appended to the DataDog metric name
     * @param int    $value      Value to be logged in DataDog
     * @param float  $sampleRate The rate of sampling
     *
     * @deprecated 2.0.0
     */
    public function __construct(string $suffix, int $value, float $sampleRate = 1.0)
    {
        parent::__construct();
        $this->value = $value;
        $this->sampleRate = $sampleRate;
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
        try {
            $this->client->histogram(
                $this->project . $this->suffix,
                $this->value,
                $this->sampleRate,
                $this->addJustKeys($tags, ['histogram'])
            );
        } catch (\Exception $e) {
            $this->client->histogram(
                $this->project . '.datadog',
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
