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
    public static function fromHandler(MessageHandlerInterface $handler): self
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
     * @template TOther of MessageHandlerInterface
     * @param HandlerType<TOther> $handlerType
     * @return bool
     */
    public function isSubtypeOf(self $handlerType): bool
    {
        return is_subclass_of($this->type, $handlerType->toString());
    }

}
