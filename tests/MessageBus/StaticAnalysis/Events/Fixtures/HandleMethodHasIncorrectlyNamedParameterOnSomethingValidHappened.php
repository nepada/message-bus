<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class HandleMethodHasIncorrectlyNamedParameterOnSomethingValidHappened implements EventSubscriber
{

    public function __invoke(SomethingValidHappenedEvent $foo): void
    {
    }

}
