<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Commands;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBus
{

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function handle(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);

        } catch (HandlerFailedException $exception) {
            $nestedExceptions = $exception->getNestedExceptions();
            if (count($nestedExceptions) === 1) {
                throw reset($nestedExceptions);
            }
            throw $exception;
        }
    }

}
