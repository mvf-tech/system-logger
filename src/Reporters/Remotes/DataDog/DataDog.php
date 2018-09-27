<?php

namespace MVF\SystemLogger\Reporters\Remotes\DataDog;

use Graze\DogStatsD\Client;
use MVF\SystemLogger\Env;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Decrement;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Gauge;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Histogram;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Increment;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Time;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Unique;

class DataDog
{
    protected $client;
    protected $project;
    protected $service;
    protected $environment;
    protected $value = '';
    protected $suffix = '';
    private $host;
    private $port;

    /**
     * DataDog constructor.
     *
     * @param EnvInterface $env Env object used to load variables from application environment
     */
    public function __construct(EnvInterface $env = null)
    {
        if ($env === null) {
            $env = new Env();
        }

        $this->project = $env->get('DATADOG_PROJECT_NAME', 'notset');
        $this->service = $env->get('DATADOG_SERVICE_NAME', 'notset');
        $this->environment = $env->get('DATADOG_ENVIRONMENT', 'notset');

        $this->host = $env->get('DATADOG_HOST_ENVVAR', 'DATADOG_HOST');
        $this->host = $env->get($this->host, '127.0.0.1');
        $this->port = $env->get('DATADOG_PORT', 8125);
        $this->client = new Client();
        $this->client->configure(
            [
                'host'      => $this->host,
                'port'      => $this->port,
                'namespace' => '',
            ]
        );
    }

    /**
     * Sets the DataDog client.
     *
     * @param Client $client The DataDog client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Gets the value that was sent to DataDog.
     *
     * @return string|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets the DataDog host as a string.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Gets the project name.
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project;
    }

    /**
     * Gets the service name.
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->service;
    }

    /**
     * Gets the environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Gets the DataDog port as an int.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Decrement constructor.
     *
     * @param string $suffix     String that is appended to the DataDog metric name
     * @param int    $value      Value to be logged in DataDog
     * @param float  $sampleRate The rate of sampling
     *
     * @return Decrement
     */
    public static function decrement(string $suffix, int $value, float $sampleRate = 1.0): Decrement
    {
        return new Decrement($suffix, $value, $sampleRate);
    }

    /**
     * Increment constructor.
     *
     * @param string $suffix     String that is appended to the DataDog metric name
     * @param int    $value      Value to be logged in DataDog
     * @param float  $sampleRate The rate of sampling
     *
     * @return Increment
     */
    public static function increment(string $suffix, int $value, float $sampleRate = 1.0): Increment
    {
        return new Increment($suffix, $value, $sampleRate);
    }

    /**
     * Histogram constructor.
     *
     * @param string $suffix     String that is appended to the DataDog metric name
     * @param int    $value      Value to be logged in DataDog
     * @param float  $sampleRate The rate of sampling
     *
     * @return Histogram
     */
    public static function histogram(string $suffix, int $value, float $sampleRate = 1.0): Histogram
    {
        return new Histogram($suffix, $value, $sampleRate);
    }

    /**
     * Gauge constructor.
     *
     * @param string $suffix String that is appended to the DataDog metric name
     * @param int    $value  Value to be logged in DataDog
     *
     * @return Gauge
     */
    public static function gauge(string $suffix, int $value): Gauge
    {
        return new Gauge($suffix, $value);
    }

    /**
     * Unique constructor.
     *
     * @param string $suffix String that is appended to the DataDog metric name
     * @param int    $value  Value to be logged in DataDog
     *
     * @return Unique
     */
    public static function unique(string $suffix, int $value): Unique
    {
        return new Unique($suffix, $value);
    }

    /**
     * Time constructor.
     *
     * @param string         $suffix String that is appended to the DataDog metric name
     * @param callable|float $time   Either a number that should be logged to DataDog
     *                               or a closure that will be timed internally
     *
     * @return Time
     */
    public static function time(string $suffix, $time): Time
    {
        return new Time($suffix, $time);
    }

    /**
     * Adds keys to the existing tags and returns the combined array.
     *
     * @param array $tags       The list of existing tags
     * @param array $additional The list that contains new tags
     *
     * @return array
     */
    protected function addJustKeys(array $tags, array $additional = []): array
    {
        $tags = array_merge($tags, $additional, ['service' => $this->service]);

        $justKeys = [];
        foreach ($tags as $key => $value) {
            if (is_numeric($key) === false) {
                $justKeys[] = $key;
            }
        }

        return array_merge($tags, $justKeys);
    }
}
