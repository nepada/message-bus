<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Middleware;

use Nepada\MessageBus\Logging\LogMessageResolver;
use Nepada\MessageBus\Logging\MessageContextResolver;
use Nepada\MessageBus\Logging\PrivateClassPropertiesExtractor;
use Nepada\MessageBus\Middleware\LoggingMiddleware;
use NepadaTests\MessageBus\Fakes\ExceptionThrowingMiddleware;
use NepadaTests\MessageBus\Fakes\StampingMiddleware;
use NepadaTests\MessageBus\Logging\Fixtures\TestCommand;
use NepadaTests\MessageBus\Logging\Fixtures\TestEvent;
use NepadaTests\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\Test\TestLogger;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class LoggingMiddlewareTest extends TestCase
{

    public function testHandleSucceeds(): void
    {
        $logger = new TestLogger();
        $middleware = $this->createLoggingMiddleware($logger);

        $message = new TestCommand();
        $envelope = new Envelope($message);

        $stack = new StackMiddleware([$middleware, new StampingMiddleware()]);
        $result = $middleware->handle($envelope, $stack);

        Assert::same([StampingMiddleware::class], array_map(fn (HandledStamp $stamp): string => $stamp->getHandlerName(), $result->all(HandledStamp::class)));
        Assert::same(
            [
                [
                    'level' => 'info',
                    'message' => 'Command handling started.',
                    'context' => [
                        'messageType' => TestCommand::class,
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'Command handling ended successfully.',
                    'context' => [
                        'messageType' => TestCommand::class,
                        'handlerType' => StampingMiddleware::class,
                    ],
                ],
            ],
            $logger->records,
        );
    }

    public function testHandleFails(): void
    {
        $logger = new TestLogger();
        $middleware = $this->createLoggingMiddleware($logger);

        $message = new TestEvent();
        $envelope = new Envelope($message);

        $nestedException = new \LogicException('Error');
        $nestedEnvelope = $envelope->with(new HandledStamp(null, 'SomeHandler'), new HandledStamp(null, 'OtherHandler'));
        $exception = new HandlerFailedException($nestedEnvelope, [$nestedException]);

        $stack = new StackMiddleware([$middleware, new ExceptionThrowingMiddleware($exception)]);
        Assert::exception(
            function () use ($middleware, $envelope, $stack): void {
                $middleware->handle($envelope, $stack);
            },
            HandlerFailedException::class,
            'Error',
        );

        Assert::same(
            [
                [
                    'level' => 'info',
                    'message' => 'Event handling started.',
                    'context' => [
                        'messageType' => TestEvent::class,
                    ],
                ],
                [
                    'level' => 'warning',
                    'message' => 'Event handling ended with error: Error',
                    'context' => [
                        'messageType' => TestEvent::class,
                        'handlerType' => 'SomeHandler',
                        'handlerType_2' => 'OtherHandler',
                        'exceptionType' => 'LogicException',
                        'exceptionMessage' => 'Error',
                    ],
                ],
            ],
            $logger->records,
        );
    }

    private function createLoggingMiddleware(LoggerInterface $logger): LoggingMiddleware
    {
        return new LoggingMiddleware(new LogMessageResolver(), new MessageContextResolver(new PrivateClassPropertiesExtractor()), $logger);
    }

}


(new LoggingMiddlewareTest())->run();
