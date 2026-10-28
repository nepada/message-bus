<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Fakes;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class ExceptionThrowingMiddleware implements MiddlewareInterface
{

    public function __construct(
        private \Throwable $exception,
    )
    {
    }

    public function handle(
        Envelope $envelope,
        StackInterface $stack,
    ): Envelope
    {
        throw $this->exception;
    }

}
