<?php

namespace spec\MVF\SystemLogger;

use MVF\SystemLogger\HostLogInterface;
use MVF\SystemLogger\RemoteLogInterface;
use MVF\SystemLogger\SystemLogger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SystemLoggerSpec extends ObjectBehavior
{
    private $host;
    private $remote;

    public function let(HostLogInterface $host, RemoteLogInterface $remote)
    {
        $this->remote = $remote;
        $this->host = $host;
        $this->remote->getValue()->willReturn(2);
        $this->remote->send(Argument::any())->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SystemLogger::class);
    }

    public function it_should_pass_log_request_to_the_logger_engine_info()
    {
        $this->host->info(Argument::any())->shouldBeCalled();
        $this->info([], '', $this->host);
    }

    public function it_should_pass_log_request_to_the_logger_engine_warning()
    {
        $this->host->warning(Argument::any())->shouldBeCalled();
        $this->warning([], '', $this->host);
    }

    public function it_should_pass_log_request_to_the_logger_engine_error()
    {
        $this->host->error(Argument::any())->shouldBeCalled();
        $this->error([], '', $this->host);
    }

    public function it_should_accept_remote_host_interface_and_log_info_on_it()
    {
        $this->remote->send(Argument::any())->shouldBeCalled();
        $this->info([], '', $this->remote);
    }

    public function it_should_accept_remote_host_interface_and_log_warning_on_it()
    {
        $this->remote->send(Argument::any())->shouldBeCalled();
        $this->warning([], '', $this->remote);
    }

    public function it_should_accept_remote_host_interface_and_log_error_on_it()
    {
        $this->remote->send(Argument::any())->shouldBeCalled();
        $this->error([], '', $this->remote);
    }

    public function it_should_not_add_message_to_the_list_of_tags_if_message_is_empty()
    {
        $this->remote->send(Argument::not(Argument::withEntry('message', '')))->shouldBeCalled();
        $this->error([], '', $this->remote);
    }

    public function it_should_add_severity_to_the_list_of_tags_on_info_send()
    {
        $this->remote->send(Argument::containing('info'))->shouldBeCalled();
        $this->info([], '', $this->remote);
    }

    public function it_should_add_severity_to_the_list_of_tags_on_warning_send()
    {
        $this->remote->send(Argument::containing('warning'))->shouldBeCalled();
        $this->warning([], '', $this->remote);
    }

    public function it_should_add_severity_to_the_list_of_tags_on_error_send()
    {
        $this->remote->send(Argument::containing('error'))->shouldBeCalled();
        $this->error([], '', $this->remote);
    }

    public function it_should_return_false_if_invalid_remote_logger_is_provided_for_info()
    {
        $this->shouldNotThrow(\Exception::class)->duringInfo([], '', (object)[]);
    }

    public function it_should_return_false_if_invalid_remote_logger_is_provided_for_warning()
    {
        $this->shouldNotThrow(\Exception::class)->duringWarning([], '', (object)[]);
    }

    public function it_should_return_false_if_invalid_remote_logger_is_provided_for_error()
    {
        $this->shouldNotThrow(\Exception::class)->duringError([], '', (object)[]);
    }

    public function it_should_replace_the_value_tag_with_the_value_passed_to_the_remote_on_info()
    {
        $this->host->info(Argument::containingString('2'))->shouldBeCalled();
        $this->info([], 'asd :0 asd', $this->remote, $this->host);
    }

    public function it_should_replace_the_value_tag_with_the_value_passed_to_the_remote_on_warning()
    {
        $this->host->warning(Argument::containingString('2'))->shouldBeCalled();
        $this->warning([], 'asd :1 asd', $this->host, $this->remote);
    }

    public function it_should_replace_the_value_tag_with_the_value_passed_to_the_remote_on_error()
    {
        $this->host->error(Argument::containingString('2'))->shouldBeCalled();
        $this->error([], 'asd :0 asd', $this->remote, $this->host);
    }

    public function it_should_throw_any_errors_during_info_log()
    {
        $this->remote->send(Argument::any())->willReturn(new \Exception());
        $this->shouldThrow(\Exception::class)->duringInfo([], 'asd :0 asd', $this->remote);
    }

    public function it_should_throw_any_last_errors_during_warning_log()
    {
        $this->remote->send(Argument::any())->willReturn(new \Exception());
        $this->shouldThrow(\Exception::class)->duringWarning([], 'asd :0 asd', $this->remote);
    }

    public function it_should_throw_any_last_errors_during_error_log()
    {
        $this->remote->send(Argument::any())->willReturn(new \Exception());
        $this->shouldThrow(\Exception::class)->duringError([], 'asd :0 asd', $this->remote);
    }

    public function it_should_throw_any_last_errors_during_info_log_if_host_logger_throws_any()
    {
        $this->host->info(Argument::any())->willReturn(new \Exception());
        $this->shouldThrow(\Exception::class)->duringInfo([], 'asd :0 asd', $this->host);
    }

    public function it_should_throw_any_last_errors_during_warning_log_if_host_logger_throws_any()
    {
        $this->host->warning(Argument::any())->willReturn(new \Exception());
        $this->shouldThrow(\Exception::class)->duringWarning([], 'asd :0 asd', $this->host);
    }

    public function it_should_throw_any_last_errors_during_error_log_if_host_logger_throws_any()
    {
        $this->host->error(Argument::any())->willReturn(new \Exception());
        $this->shouldThrow(\Exception::class)->duringError([], 'asd :0 asd', $this->host);
    }

    public function it_should_not_throw_errors_on_info_if_there_are_non()
    {
        $this->host->info(Argument::containingString('2'))->shouldBeCalled();
        $this->shouldNotThrow(\Exception::class)->duringInfo([], 'asd :0 asd', $this->remote, $this->host);
    }

    public function it_should_not_throw_errors_on_warning_if_there_are_non()
    {
        $this->host->warning(Argument::containingString('2'))->shouldBeCalled();
        $this->shouldNotThrow(\Exception::class)->duringWarning([], 'asd :1 asd', $this->host, $this->remote);
    }

    public function it_should_not_throw_errors_on_error_if_there_are_non()
    {
        $this->host->error(Argument::containingString('2'))->shouldBeCalled();
        $this->shouldNotThrow(\Exception::class)->duringError([], 'asd :0 asd', $this->remote, $this->host);
    }
}
