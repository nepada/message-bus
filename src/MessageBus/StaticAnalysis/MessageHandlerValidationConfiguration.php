<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Nepada\MessageBus\Commands\Command;
use Nepada\MessageBus\Events\Event;

class MessageHandlerValidationConfiguration
{

    private bool $handlerClassMustBeFinal;

    private bool $messageClassMustBeFinal;

    private string $handleMethodParameterName;

    private string $handleMethodParameterType;

    private string $messageClassSuffix;

    private string $handlerClassSuffix;

    private string $handlerClassPrefixRegex;

    public function __construct(
        bool $handlerClassMustBeFinal = true,
        bool $messageClassMustBeFinal = true,
        string $handleMethodParameterName = 'message',
        string $handleMethodParameterType = 'object',
        string $messageClassSuffix = '',
        string $handlerClassSuffix = '',
        string $handlerClassPrefixRegex = '',
    )
    {
        $this->handlerClassMustBeFinal = $handlerClassMustBeFinal;
        $this->messageClassMustBeFinal = $messageClassMustBeFinal;
        $this->handleMethodParameterName = $handleMethodParameterName;
        $this->handleMethodParameterType = $handleMethodParameterType;
        $this->messageClassSuffix = $messageClassSuffix;
        $this->handlerClassSuffix = $handlerClassSuffix;
        $this->handlerClassPrefixRegex = $handlerClassPrefixRegex;
    }

    public static function command(): self
    {
        $configuration = new self();

        $configuration->handleMethodParameterName = 'command';
        $configuration->handleMethodParameterType = Command::class;

        $configuration->messageClassSuffix = 'Command';
        $configuration->handlerClassSuffix = 'Handler';
        $configuration->handlerClassPrefixRegex = '';

        return $configuration;
    }

    public static function event(): self
    {
        $configuration = new self();

        $configuration->handleMethodParameterName = 'event';
        $configuration->handleMethodParameterType = Event::class;

        $configuration->messageClassSuffix = 'Event';
        $configuration->handlerClassSuffix = '';
        $configuration->handlerClassPrefixRegex = '(.+)On';

        return $configuration;
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

}
