<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Middleware;

use Nepada\MessageBus\Logging\LogMessageResolver;
use Nepada\MessageBus\Logging\MessageContextResolver;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class LoggingMiddleware implements MiddlewareInterface
{

    private LogMessageResolver $logMessageResolver;

    private MessageContextResolver $messageContextResolver;

    private LoggerInterface $logger;

    public function __construct(
        LogMessageResolver $logMessageResolver,
        MessageContextResolver $messageContextResolver,
        ?LoggerInterface $logger = null,
    )
    {
        $this->logMessageResolver = $logMessageResolver;
        $this->messageContextResolver = $messageContextResolver;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @throws ExceptionInterface
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->info(
            $this->logMessageResolver->getHandlingStartedMessage($envelope),
            $this->messageContextResolver->getContext($envelope),
        );

        try {
            $result = $stack->next()->handle($envelope, $stack);
            $this->logger->info(
                $this->logMessageResolver->getHandlingEndedSuccessfullyMessage($result),
                $this->messageContextResolver->getContext($result),
            );
            return $result;

        } catch (\Throwable $exception) {
            $this->logger->warning(
                $this->logMessageResolver->getHandlingEndedWithErrorMessage($envelope, $exception),
                $this->messageContextResolver->getContext($envelope, $exception),
            );

            throw $exception;
        }
    }

}
