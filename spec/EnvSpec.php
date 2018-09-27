<?php

namespace spec\MVF\SystemLogger;

use MVF\SystemLogger\Env;
use MVF\SystemLogger\Reporters\Remotes\DataDog\EnvInterface;
use PhpSpec\ObjectBehavior;

class EnvSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Env::class);
    }

    public function it_should_implement_env_interface()
    {
        $this->shouldHaveType(EnvInterface::class);
    }

    public function it_should_return_default_value_if_env_is_not_set()
    {
        $this->get('', 'default')->shouldReturn('default');
    }

    public function it_should_return_env_variable()
    {
        putenv('TEST=something');
        $this->get('TEST', 'default')->shouldReturn('something');
    }

    public function it_should_return_integers()
    {
        putenv('TEST=1000');
        $this->get('TEST', '0')->shouldReturn(1000);
    }
}
