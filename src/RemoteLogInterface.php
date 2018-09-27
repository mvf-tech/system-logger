<?php

namespace MVF\SystemLogger;

interface RemoteLogInterface
{
    /**
     * Sends the message to the DataDog.
     *
     * @param array $tags The list of tags to be sent
     *
     * @return \Exception|null
     */
    public function send(array $tags);

    /**
     * Gets the value that was sent to DataDog.
     *
     * @return string|int
     */
    public function getValue();
}
