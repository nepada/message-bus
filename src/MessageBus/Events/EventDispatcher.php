<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Events;

/**
 * Enqueues events that are raised for later dispatch.
 */
interface EventDispatcher
{

    public function dispatch(Event $event): void;

}
