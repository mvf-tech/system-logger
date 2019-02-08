<?php

namespace spec\MVF\SystemLogger\Reporters\Hosts;

use MVF\SystemLogger\HostLogInterface;
use MVF\SystemLogger\Reporters\Hosts\CommandLine;
use PhpSpec\ObjectBehavior;

class CommandLineSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CommandLine::class);
    }

    public function it_should_implement_host_log_interface()
    {
        $this->shouldHaveType(HostLogInterface::class);
    }

    public function it_should_return_true_on_info_log()
    {
        $this->info('')->shouldReturn(null);
    }

    public function it_should_return_true_on_warning_log()
    {
        $this->warning('')->shouldReturn(null);
    }

    public function it_should_return_true_on_error_log()
    {
        $this->error('')->shouldReturn(null);
    }
}
