<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class HandleMethodHasMoreParametersOnSomethingValidHappened implements EventSubscriber
{

    /**
     * @param mixed $foo
     * @param mixed $bar
     */
    public function __invoke($foo, $bar): void
    {
    }

}
