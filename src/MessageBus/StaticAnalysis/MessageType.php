<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Nepada\MessageBus\Commands\Command;
use Nepada\MessageBus\Events\Event;
use Nepada\MessageBus\StaticAnalysis\Rules\ClassNameHasSuffixRule;

final readonly class MessageType
{

    /**
     * @var class-string
     */
    private string $type;

    /**
     * @param class-string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromMessage(object $message): self
    {
        return new self($message::class);
    }

    /**
     * @param class-string $type
     * @return MessageType
     */
    public static function fromString(string $type): self
    {
        return new self($type);
    }

    /**
     * @return class-string
     */
    public function toString(): string
    {
        return $this->type;
    }

    public function getGeneralType(): string
    {
        if (is_subclass_of($this->type, Command::class)) {
            return 'Command';

        } elseif (is_subclass_of($this->type, Event::class)) {
            return 'Event';

        } else {
            return 'Message';
        }
    }

    public function isHandlerRequired(): bool
    {
        return ! is_subclass_of($this->type, Event::class);
    }

    /**
     * @return string message name without namespace and suffix
     * @throws StaticAnalysisFailedException
     */
    public function shortName(string $suffix): string
    {
        $messageTypeReflection = ReflectionHelper::requireClassReflection($this->toString());

        $rule = new ClassNameHasSuffixRule($suffix);
        $rule->validate($this->toString());

        preg_match($rule->getRegexPattern(), $messageTypeReflection->getShortName(), $matches);

        return (string) $matches[1];
    }

}
