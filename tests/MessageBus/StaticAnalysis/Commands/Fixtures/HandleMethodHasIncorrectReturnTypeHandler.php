<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class HandleMethodHasIncorrectReturnTypeHandler implements CommandHandler
{

    public function __invoke(ValidCommand $command): string
    {
        return '';
    }

}
