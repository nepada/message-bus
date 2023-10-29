<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\Event;

final readonly class ValidReadOnlyEvent implements Event
{

    public function __construct(
        public int $value,
    )
    {
    }

}
