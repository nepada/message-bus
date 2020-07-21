<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class HandleMethodHasNullReturnTypeOnSomethingValidHappened implements EventSubscriber
{

    /**
     * @param SomethingValidHappenedEvent $event
     * @return mixed
     */
    public function __invoke(SomethingValidHappenedEvent $event)
    {
        return null;
    }

}
