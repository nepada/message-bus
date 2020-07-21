<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Middleware;

use Nepada\MessageBus\Middleware\AlreadyHandlingOtherMessageException;
use Nepada\MessageBus\Middleware\PreventNestedHandlingMiddleware;
use NepadaTests\MessageBus\Fakes\ExceptionThrowingMiddleware;
use NepadaTests\MessageBus\Fakes\StampingMiddleware;
use NepadaTests\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class PreventNestedHandlingMiddlewareTest extends TestCase
{

    public function testCallsNextMiddlewareWithCorrectMessageAndReturnsCorrectResult(): void
    {
        $middleware = new PreventNestedHandlingMiddleware();

        $stack = new StackMiddleware([
            $middleware,
            new StampingMiddleware(),
        ]);
        $result = $middleware->handle($this->createTestEnvelope(), $stack);

        /** @var HandledStamp[] $handledStamps */
        $handledStamps = $result->all(HandledStamp::class);
        Assert::count(1, $handledStamps);
        Assert::same(StampingMiddleware::class, $handledStamps[0]->getHandlerName());
    }

    public function testHandleFailsWhenCurrentlyHandling(): void
    {
        $middleware = new PreventNestedHandlingMiddleware();

        $stack = new StackMiddleware([
            $middleware,
            $middleware,
        ]);
        Assert::exception(
            function () use ($middleware, $stack): void {
                $middleware->handle($this->createTestEnvelope(), $stack);
            },
            AlreadyHandlingOtherMessageException::class,
        );
    }

    public function testSerialHandling(): void
    {
        $middleware = new PreventNestedHandlingMiddleware();
        $exception = new \RuntimeException('Middleware failed');
        $failingMiddleware = new ExceptionThrowingMiddleware($exception);

        Assert::noError(function () use ($middleware): void {
            $middleware->handle($this->createTestEnvelope(), new StackMiddleware([$middleware]));
        });

        Assert::exception(
            function () use ($middleware, $failingMiddleware): void {
                $middleware->handle($this->createTestEnvelope(), new StackMiddleware([$middleware, $failingMiddleware]));
            },
            get_class($exception),
            $exception->getMessage(),
        );

        Assert::noError(function () use ($middleware): void {
            $middleware->handle($this->createTestEnvelope(), new StackMiddleware([$middleware]));
        });
    }

    private function createTestEnvelope(): Envelope
    {
        return new Envelope((object) ['testMessage' => true], [new BusNameStamp('test')]);
    }

}


(new PreventNestedHandlingMiddlewareTest())->run();
