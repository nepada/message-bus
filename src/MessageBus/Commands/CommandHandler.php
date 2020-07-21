<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Commands;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Command handler implementation must adhere to these rules:
 * - class must be named <command-name>Handler
 * - class must be final
 * - class must implement method named `__invoke`
 * - `__invoke` method must have exactly one parameter named `$command`
 * - `__invoke` method parameter must be typehinted with specific command class
 * - `__invoke` method return type must be `void`
 * - `__invoke` method must be annotated with `@throws` tags if specific exceptions can be thrown
 *
 * Example:
 * final class DoSomethingHandler implements CommandHandler
 * {
 *      public function __invoke(DoSomethingCommand $command): void {}
 * }
 */
interface CommandHandler extends MessageHandlerInterface
{

}
