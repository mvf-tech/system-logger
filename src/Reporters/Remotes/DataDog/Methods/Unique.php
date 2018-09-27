<?php

namespace MVF\SystemLogger\Reporters\Remotes\DataDog\Methods;

use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\DataDog;

class Unique extends DataDog implements RemoteLogInterface
{
    /**
     * Unique constructor.
     *
     * @param string $suffix String that is appended to the DataDog metric name
     * @param int    $value  Value to be logged in DataDog
     *
     * @deprecated 4.0.0
     */
    public function __construct(string $suffix, int $value)
    {
        parent::__construct();
        $this->value = $value;
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
            $this->client->set(
                $this->project . '.' . $this->environment . $this->suffix,
                $this->value,
                $this->addJustKeys($tags, ['unique'])
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
