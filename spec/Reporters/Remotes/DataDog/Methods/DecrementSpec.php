<?php

namespace spec\MVF\SystemLogger\Reporters\Remotes\DataDog\Methods;

use Graze\DogStatsD\Client;
use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Decrement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecrementSpec extends ObjectBehavior
{
    private $client;

    public function let(Client $client)
    {
        $this->client = $client;
        $this->beConstructedWith('', 1);
    }

    public function it_is_initializable()
    {
        $this->setClient($this->client);
        $this->shouldHaveType(Decrement::class);
    }

    public function it_should_implement_data_dog_interface()
    {
        $this->setClient($this->client);
        $this->shouldHaveType(RemoteLogInterface::class);
    }

    public function it_should_return_null_if_there_was_no_errors()
    {
        $this->setClient($this->client);
        $this->send([])->shouldReturn(null);
    }

    public function it_should_call_decrement_function_on_client()
    {
        $this->setClient($this->client);
        $this->client->decrement(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_return_the_exception_if_one_was_thrown()
    {
        $exception = new \Exception();
        $this->setClient($this->client);
        $this->client->histogram(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->client->decrement(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->willThrow($exception);
        $this->send([])->shouldReturn($exception);
    }

    public function it_should_be_possible_to_set_a_sample_rate_in_the_constructor()
    {
        $this->beConstructedWith('', 1, 0.5);
        $this->setClient($this->client);
        $this->client->decrement(
            Argument::any(),
            1,
            0.5,
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }

    public function it_should_add_keys_to_tags_before_send()
    {
        $this->setClient($this->client);
        $this->client->decrement(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            Argument::containing('message')
        )->shouldBeCalled();
        $this->send(['message' => 'test']);
    }

    public function it_should_add_suffix_to_metric_name()
    {
        $this->beConstructedWith('test_name', 1);
        $this->setClient($this->client);
        $this->client->decrement(
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
        $this->client->decrement(
            Argument::not(Argument::containingString('..')),
            1,
            Argument::any(),
            Argument::any()
        )->shouldBeCalled();
        $this->send([]);
    }
}
