<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Events;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final readonly class MessengerEventDispatcher implements EventDispatcher
{

    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {
    }

    public function dispatch(Event $event): void
    {
        $this->messageBus->dispatch($event, [new DispatchAfterCurrentBusStamp()]);
    }

}
