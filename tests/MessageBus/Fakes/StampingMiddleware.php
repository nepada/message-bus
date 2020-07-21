<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Fakes;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class StampingMiddleware implements MiddlewareInterface
{

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $envelope->with(new HandledStamp(true, self::class));
        return $stack->next()->handle($envelope, $stack);
    }

}
