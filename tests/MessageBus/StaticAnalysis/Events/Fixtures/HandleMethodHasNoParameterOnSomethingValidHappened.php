<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class HandleMethodHasNoParameterOnSomethingValidHappened implements EventSubscriber
{

    public function __invoke(): void
    {
    }

}
