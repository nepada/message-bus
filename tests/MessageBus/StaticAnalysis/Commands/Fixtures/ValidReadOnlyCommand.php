<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\Command;

final readonly class ValidReadOnlyCommand implements Command
{

    public function __construct(
        public int $value,
    )
    {
    }

}
