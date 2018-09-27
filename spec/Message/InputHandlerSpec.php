<?php

namespace spec\MVF\SystemLogger\Message;

use MVF\SystemLogger\Message\InputHandler;
use PhpSpec\ObjectBehavior;

class InputHandlerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([], '');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(InputHandler::class);
    }

    public function it_should_have_get_message_function()
    {
        $this->getMessage();
    }

    public function it_should_have_get_tags_function()
    {
        $this->getTags();
    }

    public function it_should_replace_placeholders_in_the_message()
    {
        $this->beConstructedWith(['asd' => 'World'], 'Hello :asd');
        $this->getMessage()->shouldReturn('Hello World');
    }

    public function it_should_only_replace_complete_placeholders_in_the_message()
    {
        $this->beConstructedWith(['asd' => 'World'], 'Hello :asdasd');
        $this->getMessage()->shouldReturn('Hello :asdasd');
    }

    public function it_should_should_not_care_about_keys_capital_letters()
    {
        $this->beConstructedWith(['Asd' => 'World'], 'Hello :asd');
        $this->getMessage()->shouldReturn('Hello World');
    }

    public function it_should_replace_all_occurances_of_the_same_key()
    {
        $this->beConstructedWith(['Asd' => 'World'], 'Hello :asd and :asd');
        $this->getMessage()->shouldReturn('Hello World and World');
    }

    public function it_should_replace_keys_next_to_each_other()
    {
        $this->beConstructedWith(['asd' => 'A', 'qwe' => 'B'], 'Hello :asd:qwe');
        $this->getMessage()->shouldReturn('Hello AB');
    }

    public function it_should_not_perform_recursive_replacement()
    {
        $this->beConstructedWith(['asd' => 'A', 'A' => 'B'], 'Hello ::asd');
        $this->getMessage()->shouldReturn('Hello :A');
    }

    public function it_should_add_message_to_tags()
    {
        $this->beConstructedWith(['asd' => 'A', 'A' => 'B'], 'Hello ::asd');
        $this->getTags()->shouldBeLike(['asd' => 'A', 'A' => 'B', 'message' => 'Hello :A']);
    }

    public function it_should_not_add_message_to_tags_if_message_is_empty()
    {
        $this->beConstructedWith(['asd' => 'A', 'A' => 'B'], '');
        $this->getTags()->shouldBeLike(['asd' => 'A', 'A' => 'B']);
    }

    public function it_should_have_a_function_to_replace_specified_value_in_the_message()
    {
        $this->beConstructedWith([], 'asd :0 asd');
        $this->replaceValue(0, 'qwe');
        $this->getMessageWithValues()->shouldReturn('asd qwe asd');
    }

    public function it_should_replace_all_value_tags_in_the_message()
    {
        $this->beConstructedWith([], 'asd :2 asd :2');
        $this->replaceValue(2, 'qwe');
        $this->getMessageWithValues()->shouldReturn('asd qwe asd qwe');
    }
}
