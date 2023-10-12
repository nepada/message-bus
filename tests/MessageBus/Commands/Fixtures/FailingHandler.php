<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

class FailingHandler implements CommandHandler
{

    /**
     * @throws CustomException
     */
    public function __invoke(FailingCommand $command): void
    {
        throw new CustomException('Runtime exception');
    }

}
