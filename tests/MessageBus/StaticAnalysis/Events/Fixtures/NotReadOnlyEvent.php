<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\Event;

final class NotReadOnlyEvent implements Event
{

    public function __construct(
        public readonly int $value,
        private string $invalid,
    )
    {
    }

    public function getInvalid(): string
    {
        return $this->invalid;
    }

}
