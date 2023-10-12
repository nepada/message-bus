<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging;

use Nepada\MessageBus\Logging\LogMessageResolver;
use NepadaTests\MessageBus\Logging\Fixtures\TestCommand;
use NepadaTests\MessageBus\Logging\Fixtures\TestEvent;
use NepadaTests\MessageBus\Logging\Fixtures\TestMessage;
use NepadaTests\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';


/**
 * @testCase
 */
class LogMessageResolverTest extends TestCase
{

    /**
     * @dataProvider provideDataForTestAllMethods
     * @param object $message
     * @param string $expectedHandlingStartedMessage
     * @param string $expectedHandlingEndedSuccessfullyMessage
     * @param string $expectedHandlingEndedWithErrorMessage
     */
    public function testAllMethods(
        object $message,
        string $expectedHandlingStartedMessage,
        string $expectedHandlingEndedSuccessfullyMessage,
        string $expectedHandlingEndedWithErrorMessage,
    ): void
    {
        $resolver = new LogMessageResolver();
        $envelope = new Envelope($message);

        Assert::same($expectedHandlingStartedMessage, $resolver->getHandlingStartedMessage($envelope));
        Assert::same($expectedHandlingEndedSuccessfullyMessage, $resolver->getHandlingEndedSuccessfullyMessage($envelope));
        Assert::same(
            $expectedHandlingEndedWithErrorMessage,
            $resolver->getHandlingEndedWithErrorMessage($envelope, new \Exception('exception-message')),
        );
    }

    /**
     * @return mixed[]
     */
    protected function provideDataForTestAllMethods(): array
    {
        return [
            [
                'message' => new TestMessage(),
                'expectedHandlingStartedMessage' => 'Message handling started.',
                'expectedHandlingEndedSuccessfullyMessage' => 'Message handling ended successfully.',
                'expectedHandlingEndedWithErrorMessage' => 'Message handling ended with error: exception-message',
            ],
            [
                'message' => new TestCommand(),
                'expectedHandlingStartedMessage' => 'Command handling started.',
                'expectedHandlingEndedSuccessfullyMessage' => 'Command handling ended successfully.',
                'expectedHandlingEndedWithErrorMessage' => 'Command handling ended with error: exception-message',
            ],
            [
                'message' => new TestEvent(),
                'expectedHandlingStartedMessage' => 'Event handling started.',
                'expectedHandlingEndedSuccessfullyMessage' => 'Event handling ended successfully.',
                'expectedHandlingEndedWithErrorMessage' => 'Event handling ended with error: exception-message',
            ],
        ];
    }

    public function testHandlerFailedExceptionWithSingleNestedException(): void
    {
        $resolver = new LogMessageResolver();
        $envelope = new Envelope(new TestMessage());

        $nestedException = new \LogicException('Error message');
        $nestedEnvelope = new Envelope(new TestMessage(), [new HandledStamp(null, 'SomeHandler')]);
        $exception = new HandlerFailedException($nestedEnvelope, [$nestedException]);

        Assert::same(
            'Message handling ended with error: Error message',
            $resolver->getHandlingEndedWithErrorMessage($envelope, $exception),
        );
    }

    public function testHandlerFailedExceptionWithMultipleNestedException(): void
    {
        $resolver = new LogMessageResolver();
        $envelope = new Envelope(new TestMessage());

        $nestedException1 = new \LogicException('Error message');
        $nestedException2 = new \RuntimeException('Error message 2');
        $nestedEnvelope = new Envelope(new TestMessage(), [new HandledStamp(null, 'SomeHandler')]);
        $exception = new HandlerFailedException($nestedEnvelope, [$nestedException1, $nestedException2]);

        Assert::same(
            'Message handling ended with error: Error message, Error message 2',
            $resolver->getHandlingEndedWithErrorMessage($envelope, $exception),
        );
    }

}


(new LogMessageResolverTest())->run();
