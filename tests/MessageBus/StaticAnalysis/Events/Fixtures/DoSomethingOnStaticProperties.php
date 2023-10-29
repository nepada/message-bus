<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class DoSomethingOnStaticProperties implements EventSubscriber
{

    public function __invoke(StaticPropertiesEvent $event): void
    {
    }

}
