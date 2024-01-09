<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Nepada\MessageBus\Commands\CommandHandler;
use Nepada\MessageBus\Events\EventSubscriber;

/**
 * @template T of CommandHandler|EventSubscriber
 */
final class HandlerType
{

    /**
     * @var class-string<T>
     */
    private string $type;

    /**
     * @param class-string<T> $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param T $handler
     * @return HandlerType<T>
     */
    public static function fromHandler(CommandHandler|EventSubscriber $handler): self
    {
        return new self($handler::class);
    }

    /**
     * @param class-string<T> $type
     * @return HandlerType<T>
     */
    public static function fromString(string $type): self
    {
        return new self($type);
    }

    /**
     * @return class-string<T>
     */
    public function toString(): string
    {
        return $this->type;
    }

    /**
     * @template TOther of CommandHandler|EventSubscriber
     * @param HandlerType<TOther> $handlerType
     */
    public function isSubtypeOf(self $handlerType): bool
    {
        return is_subclass_of($this->type, $handlerType->toString());
    }

}
