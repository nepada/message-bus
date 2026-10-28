<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Nepada\MessageBus\Commands\Command;
use Nepada\MessageBus\Events\Event;

readonly class MessageHandlerValidationConfiguration
{

    public function __construct(
        private bool $handlerClassMustBeFinal = true,
        private bool $messageClassMustBeFinal = true,
        private string $handleMethodParameterName = 'message',
        private string $handleMethodParameterType = 'object',
        private string $messageClassSuffix = '',
        private string $handlerClassSuffix = '',
        private string $handlerClassPrefixRegex = '',
        private bool $messageClassMustBeReadOnly = true,
    )
    {
    }

    public static function command(bool $bleedingEdge = false): self
    {
        return new self(
            handleMethodParameterName: 'command',
            handleMethodParameterType: Command::class,
            messageClassSuffix: 'Command',
            handlerClassSuffix: 'Handler',
            handlerClassPrefixRegex: '',
        );
    }

    public static function event(bool $bleedingEdge = false): self
    {
        return new self(
            handleMethodParameterName: 'event',
            handleMethodParameterType: Event::class,
            messageClassSuffix: 'Event',
            handlerClassSuffix: '',
            handlerClassPrefixRegex: '(.+)On',
        );
    }

    public function shouldHandlerClassBeFinal(): bool
    {
        return $this->handlerClassMustBeFinal;
    }

    public function shouldMessageClassBeFinal(): bool
    {
        return $this->messageClassMustBeFinal;
    }

    public function getHandleMethodParameterName(): string
    {
        return $this->handleMethodParameterName;
    }

    public function getHandleMethodParameterType(): string
    {
        return $this->handleMethodParameterType;
    }

    public function getMessageClassSuffix(): string
    {
        return $this->messageClassSuffix;
    }

    public function getHandlerClassSuffix(): string
    {
        return $this->handlerClassSuffix;
    }

    public function getHandlerClassPrefixRegex(): string
    {
        return $this->handlerClassPrefixRegex;
    }

    public function shouldMessageClassBeReadOnly(): bool
    {
        return $this->messageClassMustBeReadOnly;
    }

}
