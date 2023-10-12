<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class HandleMethodHasMoreParametersHandler implements CommandHandler
{

    public function __invoke(mixed $foo, mixed $bar): void
    {
    }

}
