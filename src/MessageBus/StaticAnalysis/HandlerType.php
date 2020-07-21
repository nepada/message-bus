<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @template T of MessageHandlerInterface
 */
final class HandlerType
{

    /**
     * @phpstan-var class-string<T>
     */
    private string $type;

    /**
     * @phpstan-param class-string<T> $type
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @phpstan-param T $handler
     * @param MessageHandlerInterface $handler
     * @return HandlerType<T>
     */
    public static function fromHandler(MessageHandlerInterface $handler): self
    {
        return new self(get_class($handler));
    }

    /**
     * @param string $type
     * @return HandlerType<T>
     */
    public static function fromString(string $type): self
    {
        return new self($type);
    }

    /**
     * @phpstan-return class-string<T>
     */
    public function toString(): string
    {
        return $this->type;
    }

    /**
     * @template TOther of MessageHandlerInterface
     * @phpstan-param HandlerType<TOther> $handlerType
     * @param HandlerType $handlerType
     * @return bool
     */
    public function isSubtypeOf(HandlerType $handlerType): bool
    {
        return is_subclass_of($this->type, $handlerType->toString());
    }

}
