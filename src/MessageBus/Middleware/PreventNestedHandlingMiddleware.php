<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * Do not allow nested handling of messages.
 */
class PreventNestedHandlingMiddleware implements MiddlewareInterface
{

    private bool $isHandling = false;

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($this->isHandling) {
            throw new AlreadyHandlingOtherMessageException('Already handling other message.');
        }

        $this->isHandling = true;

        try {
            return $stack->next()->handle($envelope, $stack);
        } finally {
            $this->isHandling = false;
        }
    }

}
