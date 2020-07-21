<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

class NotFinalHandler implements CommandHandler
{

    public function __invoke(ValidCommand $command): void
    {
    }

}
