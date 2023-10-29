<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures;

use Nepada\MessageBus\Commands\Command;

final class StaticPropertiesCommand implements Command
{

    public static int $invalid = 1;

}
