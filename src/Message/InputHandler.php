<?php

namespace MVF\SystemLogger\Message;

class InputHandler
{
    private $message;
    private $messageWithValues;
    private $tags;

    /**
     * InputHandler constructor.
     *
     * @param array  $tags    The list of tags to be processed
     * @param string $message The message to be added to the list of tags
     */
    public function __construct(array $tags, string $message)
    {
        $patterns = [];
        $replacements = [];
        foreach ($tags as $key => $values) {
            if (is_numeric($key) === false) {
                $unique = uniqid();
                $patterns[] = "/${unique}/";
                $replacements[] = $values;
                $message = $this->replaceTag($key, $unique, $message);
            }
        }

        $this->message = preg_replace($patterns, $replacements, $message);
        if (empty($this->message) === false) {
            $tags['message'] = $this->message;
        }

        $this->tags = $tags;
        $this->messageWithValues = $this->message;
    }

    /**
     * Replaces value that is specified by an index (:0 or :1) in the string.
     *
     * @param int    $index The index of the logger value to be replaced, :0 is replace with the value of the first
     *                      logger that is passed to info/warning/error function in the SystemLogger class
     * @param string $value The string to be used instead of index (:0 or :1)
     */
    public function replaceValue(int $index, string $value)
    {
        $this->messageWithValues = $this->replaceTag(
            $index,
            $value,
            $this->messageWithValues
        );
    }

    /**
     * Gets the message with replaced placeholders.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Gets the message with replaced placeholders and values.
     *
     * @return string
     */
    public function getMessageWithValues()
    {
        return $this->messageWithValues;
    }

    /**
     * Gets the list of tags.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Replaces the specified tag in the provided message.
     *
     * @param string $key         The placeholder that should be replaced
     * @param string $replacement The list of replacements
     * @param string $message     Message where the values are inserted
     *
     * @return string
     */
    private function replaceTag(string $key, string $replacement, string $message): string
    {
        return preg_replace("/(:${key}(?=[^A-z]|\\Z))/i", $replacement, $message);
    }
}
