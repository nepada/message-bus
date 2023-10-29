<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class DoSomethingOnNotReadOnly implements EventSubscriber
{

    public function __invoke(NotReadOnlyEvent $event): void
    {
    }

}
