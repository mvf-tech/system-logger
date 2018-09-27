<?php

namespace spec\MVF\SystemLogger\Reporters\Remotes\DataDog;

use MVF\SystemLogger\Reporters\Remotes\DataDog\DataDog;
use MVF\SystemLogger\Reporters\Remotes\DataDog\EnvInterface;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Decrement;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Gauge;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Histogram;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Increment;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Time;
use MVF\SystemLogger\Reporters\Remotes\DataDog\Methods\Unique;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DataDogSpec extends ObjectBehavior
{
    private $env;

    public function let(EnvInterface $env)
    {
        $env->get(Argument::any(), Argument::any())->willReturn('');
        $env->get('DATADOG_HOST_ENVVAR', 'DATADOG_HOST')->willReturn('DATADOG_HOST');
        $env->get('DATADOG_HOST', '127.0.0.1')->willReturn('127.0.0.1');
        $env->get('DATADOG_PORT', 8125)->willReturn(8125);
        $env->get('DATADOG_PROJECT_NAME', 'notset')->willReturn('notset');
        $env->get('DATADOG_SERVICE_NAME', 'notset')->willReturn('notset');
        $env->get('DATADOG_ENVIRONMENT', 'notset')->willReturn('notset');

        $this->env = $env;
        $this->beConstructedWith($this->env);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DataDog::class);
    }

    public function it_function_it_should_have_function_to_get_value()
    {
        $this->getValue()->shouldReturn('');
    }

    public function it_should_use_the_data_dog_host_env_var_by_default()
    {
        $this->env->get('DATADOG_HOST', '127.0.0.1')->willReturn('something');
        $this->getHost()->shouldReturn('something');
    }

    public function it_should_use_host_env_var_to_get_host_if_it_is_present()
    {
        $this->env->get('DATADOG_HOST_ENVVAR', 'DATADOG_HOST')->willReturn('HOST');
        $this->env->get('HOST', '127.0.0.1')->willReturn('something');
        $this->getHost()->shouldReturn('something');
    }

    public function it_should_have_default_datadog_project_name()
    {
        $this->getProjectName()->shouldReturn('notset');
    }

    public function it_should_load_datadog_project_name_from_env()
    {
        $this->env->get('DATADOG_PROJECT_NAME', 'notset')->willReturn('test');
        $this->getProjectName()->shouldReturn('test');
    }

    public function it_should_have_default_datadog_service_name()
    {
        $this->getServiceName()->shouldReturn('notset');
    }

    public function it_should_load_datadog_service_name_from_env()
    {
        $this->env->get('DATADOG_SERVICE_NAME', 'notset')->willReturn('test');
        $this->getServiceName()->shouldReturn('test');
    }

    public function it_should_have_default_datadog_environment_name()
    {
        $this->getEnvironment()->shouldReturn('notset');
    }

    public function it_should_load_service_datadog_environment_from_env()
    {
        $this->env->get('DATADOG_ENVIRONMENT', 'notset')->willReturn('test');
        $this->getEnvironment()->shouldReturn('test');
    }

    public function it_should_have_a_default_port_number()
    {
        $this->getPort()->shouldReturn(8125);
    }

    public function it_should_load_port_from_env()
    {
        $this->env->get('DATADOG_PORT', 8125)->willReturn(1000);
        $this->getPort()->shouldReturn(1000);
    }

    public function it_should_have_a_static_function_decrement_that_returns_decrement_object()
    {
        $this->decrement('suffix', 1, 1.0)->shouldReturnAnInstanceOf(Decrement::class);
    }

    public function it_should_have_a_static_function_increment_that_returns_increment_object()
    {
        $this->increment('suffix', 1, 1.0)->shouldReturnAnInstanceOf(Increment::class);
    }

    public function it_should_have_a_static_function_histogram_that_returns_histogram_object()
    {
        $this->histogram('suffix', 1, 1.0)->shouldReturnAnInstanceOf(Histogram::class);
    }

    public function it_should_have_a_static_function_gauge_that_returns_gauge_object()
    {
        $this->gauge('suffix', 1)->shouldReturnAnInstanceOf(Gauge::class);
    }

    public function it_should_have_a_static_function_unique_that_returns_unique_object()
    {
        $this->unique('suffix', 1)->shouldReturnAnInstanceOf(Unique::class);
    }

    public function it_should_have_a_static_function_time_that_returns_time_object()
    {
        $this->time('suffix', 1)->shouldReturnAnInstanceOf(Time::class);
    }
}
