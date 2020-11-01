<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class MessageTypeExtractor
{

    public const METHOD_NAME = '__invoke';

    /**
     * @template T of MessageHandlerInterface
     * @param HandlerType<T> $handlerType
     * @return MessageType
     * @throws StaticAnalysisFailedException
     */
    public function extract(HandlerType $handlerType): MessageType
    {
        $handleMethod = ReflectionHelper::requireMethodReflection($handlerType->toString(), self::METHOD_NAME);

        $handleMethodParameters = $handleMethod->getParameters();
        $handleMethodParameter = $handleMethodParameters[0];

        $parameterType = $handleMethodParameter->getType();
        if ($parameterType === null) {
            throw new \LogicException(
                sprintf('Handle method parameter type of class "%s" must be defined in this context.', $handlerType->toString()),
            );
        }

        return MessageType::fromString($parameterType->getName());
    }

}
