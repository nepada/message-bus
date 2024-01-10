<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Events;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final readonly class MessengerEventDispatcher implements EventDispatcher
{

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(Event $event): void
    {
        $this->messageBus->dispatch($event, [new DispatchAfterCurrentBusStamp()]);
    }

}
