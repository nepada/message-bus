<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Commands;

interface CommandBus
{

    public function handle(Command $command): void;

}
