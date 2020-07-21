<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class HandleMethodHasNoReturnTypeHandler implements CommandHandler
{

    /**
     * @param ValidCommand $command
     * @return mixed
     */
    public function __invoke(ValidCommand $command)
    {
        return null;
    }

}
