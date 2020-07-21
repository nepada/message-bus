<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Logging;

use Nepada\MessageBus\Logging\MessageContextResolver;
use Nepada\MessageBus\Logging\PrivateClassPropertiesExtractor;
use NepadaTests\MessageBus\Logging\Fixtures\TestLoggableMessage;
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
class MessageContextResolverTest extends TestCase
{

    public function testRegularMessage(): void
    {
        $resolver = $this->createMessageContextResolver();
        $envelope = new Envelope(new TestMessage());

        Assert::equal(
            [
                'messageType' => TestMessage::class,
            ],
            $resolver->getContext($envelope),
        );
    }

    public function testMessageWithProperties(): void
    {
        $resolver = $this->createMessageContextResolver();
        $message = new TestLoggableMessage(
            [
                'integerAttribute' => 1,
                'stringAttribute' => 'string',
                'arrayAttribute' => [
                    'nestedAttribute' => 'nested',
                ],
            ],
        );
        $envelope = new Envelope($message);

        Assert::equal(
            [
                'messageType' => TestLoggableMessage::class,
                'integerAttribute' => 1,
                'stringAttribute' => 'string',
                'arrayAttribute' => [
                    'nestedAttribute' => 'nested',
                ],
            ],
            $resolver->getContext($envelope),
        );
    }

    public function testPrefixing(): void
    {
        $resolver = $this->createMessageContextResolver('prefix_');
        $message = new TestLoggableMessage(
            [
                'integerAttribute' => 1,
                'stringAttribute' => 'string',
                'arrayAttribute' => [
                    'nestedAttribute' => 'nested',
                ],
            ],
        );
        $envelope = new Envelope($message);

        Assert::equal(
            [
                'prefix_messageType' => TestLoggableMessage::class,
                'prefix_integerAttribute' => 1,
                'prefix_stringAttribute' => 'string',
                'prefix_arrayAttribute' => [
                    'nestedAttribute' => 'nested',
                ],
            ],
            $resolver->getContext($envelope),
        );
    }

    public function testLoggableMessageWithCollidingContext(): void
    {
        $resolver = $this->createMessageContextResolver();
        $message = new TestLoggableMessage(
            [
                'messageType' => 1,
                'uniqueKey' => 1,
            ],
        );
        $envelope = new Envelope($message);

        Assert::error(
            function () use ($resolver, $envelope): void {
                Assert::equal(
                    [
                        'messageType' => TestLoggableMessage::class,
                        'disambiguated_messageType' => 1,
                        'uniqueKey' => 1,
                    ],
                    $resolver->getContext($envelope),
                );
            },
            E_USER_WARNING,
            'Message context merge failed with following duplicate keys: "messageType"',
        );
    }

    public function testSingleHandledStamp(): void
    {
        $resolver = $this->createMessageContextResolver();
        $envelope = new Envelope(new TestMessage(), [new HandledStamp(null, 'SomeHandlerType')]);

        Assert::equal(
            [
                'messageType' => TestMessage::class,
                'handlerType' => 'SomeHandlerType',
            ],
            $resolver->getContext($envelope),
        );
    }

    public function testMultipleHandledStamp(): void
    {
        $resolver = $this->createMessageContextResolver();
        $envelope = new Envelope(new TestMessage(), [new HandledStamp(null, 'SomeHandler'), new HandledStamp(null, 'OtherHandler')]);

        Assert::equal(
            [
                'messageType' => TestMessage::class,
                'handlerType' => 'SomeHandler',
                'handlerType_2' => 'OtherHandler',
            ],
            $resolver->getContext($envelope),
        );
    }

    public function testLogicException(): void
    {
        $resolver = $this->createMessageContextResolver();
        $envelope = new Envelope(new TestMessage());
        $exception = new \LogicException('Error message');

        Assert::equal(
            [
                'messageType' => TestMessage::class,
                'exceptionMessage' => 'Error message',
                'exceptionType' => \LogicException::class,
            ],
            $resolver->getContext($envelope, $exception),
        );
    }

    public function testHandlerFailedExceptionWithSingleNestedException(): void
    {
        $resolver = $this->createMessageContextResolver();
        $envelope = new Envelope(new TestMessage());

        $nestedException = new \LogicException('Error message');
        $nestedEnvelope = new Envelope(new TestMessage(), [new HandledStamp(null, 'SomeHandler')]);
        $exception = new HandlerFailedException($nestedEnvelope, [$nestedException]);

        Assert::equal(
            [
                'messageType' => TestMessage::class,
                'handlerType' => 'SomeHandler',
                'exceptionType' => \LogicException::class,
                'exceptionMessage' => 'Error message',
            ],
            $resolver->getContext($envelope, $exception),
        );
    }

    public function testHandlerFailedExceptionWithMultipleNestedException(): void
    {
        $resolver = $this->createMessageContextResolver();
        $envelope = new Envelope(new TestMessage());

        $nestedException1 = new \LogicException('Error message');
        $nestedException2 = new \RuntimeException('Error message 2');
        $nestedEnvelope = new Envelope(new TestMessage(), [new HandledStamp(null, 'SomeHandler')]);
        $exception = new HandlerFailedException($nestedEnvelope, [$nestedException1, $nestedException2]);

        Assert::equal(
            [
                'messageType' => TestMessage::class,
                'handlerType' => 'SomeHandler',
                'exceptionType' => \LogicException::class,
                'exceptionMessage' => 'Error message',
                'exceptionType_2' => \RuntimeException::class,
                'exceptionMessage_2' => 'Error message 2',
            ],
            $resolver->getContext($envelope, $exception),
        );
    }

    private function createMessageContextResolver(string $prefix = ''): MessageContextResolver
    {
        return new MessageContextResolver(new PrivateClassPropertiesExtractor(), $prefix);
    }

}


(new MessageContextResolverTest())->run();
