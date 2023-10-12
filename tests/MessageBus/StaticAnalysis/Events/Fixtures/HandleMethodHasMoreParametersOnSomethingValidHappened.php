<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class HandleMethodHasMoreParametersOnSomethingValidHappened implements EventSubscriber
{

    public function __invoke(mixed $foo, mixed $bar): void
    {
    }

}
