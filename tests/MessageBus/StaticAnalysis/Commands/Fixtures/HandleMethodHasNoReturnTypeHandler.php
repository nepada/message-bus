<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\CommandHandler;

final class HandleMethodHasNoReturnTypeHandler implements CommandHandler
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     * @param ValidCommand $command
     */
    public function __invoke(ValidCommand $command)
    {
        throw new \Exception();
    }

}
