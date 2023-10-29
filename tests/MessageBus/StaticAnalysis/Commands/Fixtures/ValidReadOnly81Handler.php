<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class ValidReadOnly81Handler implements CommandHandler
{

    public function __invoke(ValidReadOnly81Command $command): void
    {
    }

}
