<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Events;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Event subscriber implementation must adhere to these rules:
 * - class must be named <do-something>On<event-name>
 * - class must be final
 * - class must implement method named `__invoke`
 * - `__invoke` method must have exactly one parameter named `$event`
 * - `__invoke` method parameter must be typehinted with specific event class
 * - `__invoke` method return type must be `void`
 * - `__invoke` method must be annotated with `@throws` tags if specific exceptions can be thrown
 *
 * Example:
 * final class DoSomethingOnSomethingHappened implements EventSubscriber
 * {
 *      public function __invoke(SomethingHappenedEvent $event): void {}
 * }
 */
interface EventSubscriber extends MessageHandlerInterface
{

}
