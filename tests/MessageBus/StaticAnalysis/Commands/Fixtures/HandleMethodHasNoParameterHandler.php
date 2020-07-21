<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class HandleMethodHasNoParameterHandler implements CommandHandler
{

    public function __invoke(): void
    {
    }

}
