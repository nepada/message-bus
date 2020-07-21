<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

interface MessageHandlerValidator
{

    /**
     * @template  T of \Symfony\Component\Messenger\Handler\MessageHandlerInterface
     * @param HandlerType<T> $handlerType
     * @throws StaticAnalysisFailedException
     */
    public function validate(HandlerType $handlerType): void;

}
