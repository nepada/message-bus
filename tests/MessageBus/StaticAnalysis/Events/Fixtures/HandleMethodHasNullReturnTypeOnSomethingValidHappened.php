<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class HandleMethodHasNullReturnTypeOnSomethingValidHappened implements EventSubscriber
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     * @param SomethingValidHappenedEvent $event
     */
    public function __invoke(SomethingValidHappenedEvent $event)
    {
        throw new \Exception();
    }

}
