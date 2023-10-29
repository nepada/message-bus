<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\EventSubscriber;

final class DoSomethingOnValidReadOnly81 implements EventSubscriber
{

    public function __invoke(ValidReadOnly81Event $event): void
    {
    }

}
