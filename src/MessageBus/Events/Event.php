<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Events;

/**
 * Event implementation must adhere to these rules:
 * - class must be named `<event-name>Event`
 * - event name should be in past tense ("something happened")
 * - event must be a simple immutable DTO
 * - event must not contain entities, only references (i.e. `int $orderId`, not `Order $order`)
 *
 * Examples of good event class names:
 * - OrderRejectedEvent
 * - UserRegisteredEvent
 */
interface Event
{

}
