<?php

namespace spec\MVF\SystemLogger\Reporters\Remotes\DataDog\Methods;

use Graze\DogStatsD\Client;
use MVF\SystemLogger\Message\InputHandler;
use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Time;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TimeSpec extends ObjectBehavior
{
    /**
     * @var Client
     */
    private $client;

    public function let(Client $client)
    {
        $this->client = $client;
        $this->beConstructedWith('', 1);
    }

    public function it_is_initializable()
    {
        $this->setClient($this->client);
        $this->shouldHaveType(Time::class);
    }

    public function it_should_implement_data_dog_interface()
    {
        $this->setClient($this->client);
        $this->shouldHaveType(RemoteLogInterface::class);
    }

    public function it_should_return_null_if_there_was_no_errors()
    {
        $this->setClient($this->client);
        $this->client->timing(
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([])->shouldReturn(null);
    }

    public function it_should_call_timing_function_on_client()
    {
        $this->setClient($this->client);
        $this->client->timing(
            Argument::any(),
            1,
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_return_exception_if_exception_is_thrown()
    {
        $exception = new \Exception();
        $this->setClient($this->client);
        $this->client->timing(
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->willThrow($exception);
        $this->client->histogram(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([])->shouldReturn($exception);
    }

    public function it_should_return_exception_if_invalid_time_input_is_provided()
    {
        $this->beConstructedWith('', '');
        $this->setClient($this->client);
        $this->send([])->shouldReturnAnInstanceOf(\InvalidArgumentException::class);
    }

    public function it_should_return_exception_if_invalid_input_is_provided_to_time(InputHandler $handler)
    {
        $exception = new \Exception();
        $handler->getMessage()->willThrow($exception);
        $this->beConstructedWith('', [$handler, 'getMessage']);
        $this->send([])->shouldReturn($exception);
    }

    public function it_should_add_keys_to_tags_before_send()
    {
        $this->setClient($this->client);
        $this->client->timing(
            Argument::any(),
            Argument::any(),
            Argument::containing('message')
        )->shouldBeCalled();
        $this->send(['message' => 'test']);
    }

    public function it_should_add_keys_to_tags_before_send_func(InputHandler $handler)
    {
        $handler->getMessage()->willReturn('');
        $this->beConstructedWith('', [$handler, 'getMessage']);
        $this->setClient($this->client);
        $this->client->timing(
            Argument::any(),
            Argument::any(),
            Argument::containing('message')
        )->shouldBeCalled();
        $this->send(['message' => 'test']);
    }

    public function it_should_call_the_closure_if_it_was_provided(InputHandler $handler)
    {
        $handler->getMessage()->shouldBeCalled();
        $this->beConstructedWith('', [$handler, 'getMessage']);
        $this->send([]);
    }

    public function it_should_add_time_to_the_list_of_sent_tags()
    {
        $this->setClient($this->client);
        $this->client->timing(
            Argument::any(),
            1,
            Argument::containing('time')
        )->shouldBeCalled();
        $this->send(['message' => 'test']);
    }

    public function it_should_add_suffix_to_metric_name()
    {
        $this->beConstructedWith('test_name', 1);
        $this->setClient($this->client);
        $this->client->timing(
            Argument::containingString('.test_name'),
            1,
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_not_have_repeating_dots_in_the_metric_name()
    {
        $this->beConstructedWith('test_name', 1);
        $this->setClient($this->client);
        $this->client->timing(
            Argument::not(Argument::containingString('..')),
            1,
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }
}
