<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\Event;

final class ValidReadOnly81Event implements Event
{

    public function __construct(
        public readonly int $value,
    )
    {
    }

}
