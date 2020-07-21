<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\Logging;

use Nepada\MessageBus\StaticAnalysis\MessageType;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class LogMessageResolver
{

    public function getHandlingStartedMessage(Envelope $envelope): string
    {
        return sprintf(
            '%s handling started.',
            MessageType::fromMessage($envelope->getMessage())->getGeneralType(),
        );
    }

    public function getHandlingEndedSuccessfullyMessage(Envelope $envelope): string
    {
        return sprintf(
            '%s handling ended successfully.',
            MessageType::fromMessage($envelope->getMessage())->getGeneralType(),
        );
    }

    public function getHandlingEndedWithErrorMessage(Envelope $envelope, \Throwable $exception): string
    {
        if ($exception instanceof HandlerFailedException) {
            $nestedExceptions = $exception->getNestedExceptions();
        } else {
            $nestedExceptions = [$exception];
        }
        $exceptionMessages = array_map(fn (\Throwable $exception): string => $exception->getMessage(), $nestedExceptions);
        $exceptionMessage = implode(', ', $exceptionMessages);
        return sprintf(
            '%s handling ended with error: %s',
            MessageType::fromMessage($envelope->getMessage())->getGeneralType(),
            $exceptionMessage,
        );
    }

}
