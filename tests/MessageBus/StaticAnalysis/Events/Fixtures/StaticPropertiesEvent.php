<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures;

use Nepada\MessageBus\Events\Event;

final class StaticPropertiesEvent implements Event
{

    public static int $invalid = 1;

}
