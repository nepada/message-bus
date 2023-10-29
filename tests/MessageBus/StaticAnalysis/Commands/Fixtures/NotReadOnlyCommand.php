<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\Command;

final class NotReadOnlyCommand implements Command
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
