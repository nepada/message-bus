<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Commands;

use Nepada\MessageBus\Commands\MessengerCommandBus;
use NepadaTests\MessageBus\Commands\Fixtures\CustomException;
use NepadaTests\MessageBus\Commands\Fixtures\FailingCommand;
use NepadaTests\MessageBus\Commands\Fixtures\FailingHandler;
use NepadaTests\TestCase;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class MessengerCommandBusTest extends TestCase
{

    public function testThrowsOriginalException(): void
    {
        $handlerLocator = new HandlersLocator([FailingCommand::class => [new FailingHandler()]]);
        $messageBus = new MessageBus([new HandleMessageMiddleware($handlerLocator)]);
        $commandBus = new MessengerCommandBus($messageBus);

        Assert::exception(
            function () use ($commandBus): void {
                $commandBus->handle(new FailingCommand());
            },
            CustomException::class,
            'Runtime exception',
        );
    }

}


(new MessengerCommandBusTest())->run();
