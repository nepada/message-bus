<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class HandleMethodHasMoreParametersHandler implements CommandHandler
{

    /**
     * @param mixed $foo
     * @param mixed $bar
     */
    public function __invoke($foo, $bar): void
    {
    }

}
