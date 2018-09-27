<?php

namespace spec\MVF\SystemLogger\Reporters\Hosts;

use MVF\SystemLogger\HostLogInterface;
use MVF\SystemLogger\Reporters\Hosts\BasicStdOut;
use PhpSpec\ObjectBehavior;

class BasicStdOutSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BasicStdOut::class);
    }

    public function it_should_implement_host_log_interface()
    {
        $this->shouldHaveType(HostLogInterface::class);
    }

    public function it_should_return_true_on_info_log()
    {
        $this->info('')->shouldReturn(null);
    }

    public function it_should_throw_exception_on_erroneous_input_to_info()
    {
        $this->info(new \stdClass())->shouldReturnAnInstanceOf(\Exception::class);
    }

    public function it_should_return_true_on_warning_log()
    {
        $this->warning('')->shouldReturn(null);
    }

    public function it_should_throw_exception_on_erroneous_input_to_warning()
    {
        $this->warning(new \stdClass())->shouldReturnAnInstanceOf(\Exception::class);
    }

    public function it_should_return_true_on_error_log()
    {
        $this->error('')->shouldReturn(null);
    }

    public function it_should_throw_exception_on_erroneous_input_to_error()
    {
        $this->error(new \stdClass())->shouldReturnAnInstanceOf(\Exception::class);
    }
}
