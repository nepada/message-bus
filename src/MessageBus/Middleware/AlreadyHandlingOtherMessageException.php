<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Middleware;

use Symfony\Component\Messenger\Exception\ExceptionInterface;

class AlreadyHandlingOtherMessageException extends \LogicException implements ExceptionInterface
{

}
