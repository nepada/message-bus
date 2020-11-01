<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

interface MessageHandlerValidator
{

    /**
     * @template T of MessageHandlerInterface
     * @param HandlerType<T> $handlerType
     * @throws StaticAnalysisFailedException
     */
    public function validate(HandlerType $handlerType): void;

}
