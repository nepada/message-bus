<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Nepada\MessageBus\Commands\CommandHandler;
use Nepada\MessageBus\Events\EventSubscriber;

interface MessageHandlerValidator
{

    /**
     * @template T of CommandHandler|EventSubscriber
     * @param HandlerType<T> $handlerType
     * @throws StaticAnalysisFailedException
     */
    public function validate(HandlerType $handlerType): void;

}
