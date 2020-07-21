<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\Fakes;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class CallbackMiddleware implements MiddlewareInterface
{

    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        ($this->callback)();
        return $stack->next()->handle($envelope, $stack);
    }

}
