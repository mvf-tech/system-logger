<?php

namespace spec\MVF\SystemLogger\Reporters\Remotes\DataDog\Methods;

use Graze\DogStatsD\Client;
use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Gauge;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GaugeSpec extends ObjectBehavior
{
    private $client;

    public function let(Client $client)
    {
        $this->client = $client;
        $this->beConstructedWith('', 1);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Gauge::class);
    }

    public function it_should_implement_data_dog_interface()
    {
        $this->shouldHaveType(RemoteLogInterface::class);
    }

    public function it_should_return_null_if_there_was_no_errors()
    {
        $this->setClient($this->client);
        $this->send([])->shouldReturn(null);
    }

    public function it_should_call_gauge_function_on_client()
    {
        $this->setClient($this->client);
        $this->client->gauge(
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_return_false_if_exception_is_thrown()
    {
        $exception = new \Exception();
        $this->setClient($this->client);
        $this->client->gauge(
            Argument::any(),
            Argument::any(),
            Argument::containing('gauge')
        )->willThrow($exception);
        $this->client->histogram(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([])->shouldReturn($exception);
    }

    public function it_should_add_keys_to_tags_before_send()
    {
        $this->setClient($this->client);
        $this->client->gauge(
            Argument::any(),
            Argument::any(),
            Argument::containing('message')
        )->shouldBeCalled();
        $this->send(['message' => 'test']);
    }

    public function it_should_add_gauge_to_the_list_of_sent_tags()
    {
        $this->setClient($this->client);
        $this->client->gauge(
            Argument::any(),
            Argument::any(),
            Argument::containing('gauge')
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_add_suffix_to_metric_name()
    {
        $this->beConstructedWith('test_name', 1);
        $this->setClient($this->client);
        $this->client->gauge(
            Argument::containingString('.test_name'),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_not_have_repeating_dots_in_the_metric_name()
    {
        $this->beConstructedWith('test_name', 1);
        $this->setClient($this->client);
        $this->client->gauge(
            Argument::not(Argument::containingString('..')),
            1,
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }
}
