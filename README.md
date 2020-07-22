Message Bus
===========

[![Build Status](https://travis-ci.org/nepada/message-bus.svg?branch=master)](https://travis-ci.org/nepada/message-bus)
[![Coverage Status](https://coveralls.io/repos/github/nepada/message-bus/badge.svg?branch=master)](https://coveralls.io/github/nepada/message-bus?branch=master)
[![Downloads this Month](https://img.shields.io/packagist/dm/nepada/message-bus.svg)](https://packagist.org/packages/nepada/message-bus)
[![Latest stable](https://img.shields.io/packagist/v/nepada/message-bus.svg)](https://packagist.org/packages/nepada/message-bus)


Opinionated message bus built on top of [symfony/messenger](https://github.com/symfony/messenger) largely based on the ideas and code base of damejidlo/message-bus, originally developed by [Ondřej Bouda](mailto:ondrej.bouda@gmail.com).


Installation
------------

Via Composer:

```sh
$ composer require nepada/message-bus
```


Conventions
-----------

We define two types of messages and corresponding message buses - commands and events.

### Commands

Command implementation must adhere to these rules:
- class must implement `Nepada\Commands\Command` interface
- class must be named `<command-name>Command`
- class must be final
- command name should be in imperative form ("do something")
- command must be a simple immutable DTO
- command must not contain entities, only references (i.e. `int $orderId`, not `Order $order`)

Examples of good command class names:
- `RejectOrderCommand`
- `CreateUserCommand`

Command handler implementation must adhere to these rules:
- class must implement `Nepada\Commands\CommandHandler` interface
- class must be named `<command-name>Handler`
- class must be final
- class must implement method named `__invoke`
- `__invoke` method must have exactly one parameter named `$command`
- `__invoke` method parameter must be typehinted with specific command class
- `__invoke` method return type must be `void`
- `__invoke` method must be annotated with `@throws` tags if specific exceptions can be thrown

Example:
```php
final class DoSomethingHandler implements \Nepada\MessageBus\Commands\CommandHandler
{
    /**
     * @param DoSomethingCommand $command
     * @throws SomeException
     */
    public function __invoke(DoSomethingCommand $command): void
    {
        // ...
    }
}
```

Every command must have exactly one handler.


### Events

Events must be dispatched during command handling only.

Event implementation must adhere to these rules:
- class must implement `Nepada\Events\Event` interface
- class must be named `<event-name>Event`
- event name should be in past tense ("something happened")
- event must be a simple immutable DTO
- event must not contain entities, only references (i.e. `int $orderId`, not `Order $order`)

Examples of good event class names:
- `OrderRejectedEvent`
- `UserRegisteredEvent`

Event subscriber implementation must adhere to these rules:
- class must implement `Nepada\Events\EventSubscriber` interface
- class must be named `<do-something>On<event-name>`
- class must be final
- class must implement method named `__invoke`
- `__invoke` method must have exactly one parameter named `$event`
- `__invoke` method parameter must be typehinted with specific event class
- `__invoke` method return type must be `void`
- `__invoke` method must be annotated with `@throws` tags if specific exceptions can be thrown

Example:
```php
final class DoSomethingOnSomethingHappened implements \Nepada\MessageBus\Events\EventSubscriber
{
     public function __invoke(SomethingHappenedEvent $event): void {}
}
```

Every event may have any number of subscribers, or none at all.


Configuration & Usage
---------------------

### Static analysis

Most of the conventions described above may be enforced by static analysis.
The analysis should be run during the compilation of DI container, triggering it at application runtime is not recommended.

```php
use Nepada\MessageBus\StaticAnalysis\ConfigurableHandlerValidator;
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageHandlerValidationConfiguration;

// Validate command handler
$commandHandlerType = HandlerType::fromString(DoSomethingHandler::class);
$commandHandlerConfiguration = MessageHandlerValidationConfiguration::command();
$commandHandlerValidator = new ConfigurableHandlerValidator($commandHandlerConfiguration);
$commandHandlerValidator->validate($commandHandlerType);

// Validate event subscriber
$eventSubscriberType = HandlerType::fromString(DoSomethingOnSomethingHappened::class);
$eventSubscriberConfiguration = MessageHandlerValidationConfiguration::event();
$eventSubscriberValidator = new ConfigurableHandlerValidator($eventSubscriberConfiguration);
$eventSubscriberValidator->validate($eventSubscriberType);
```

Use `MessageTypeExtractor` to retrieve the message type that a given command handler or event subscriber handles:
```php
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageTypeExtractor;

// Extracting handled message type
$messageTypeExtractor = new MessageTypeExtractor();
$commandHandlerType = HandlerType::fromString(DoSomethingHandler::class);
$messageTypeExtractor->extract($commandHandlerType); // MessageType instance for DoSomethingCommand
```

### Logging

`LoggingMiddleware` implements logging into standard PSR-3 logger.
Start of message handling and its success or failure are logged separately.
Logging context is filled with the extracted attributes of command or event DTO.

### Nested message handling
 
Generally, it's not a good idea to execute commands from within another command handler.
You can completely forbid this behavior with `PreventNestedHandlingMiddleware`.

### Configuration

It is completely up to you to use the provided building blocks together with Symfony Messenger and configure one or more instances of command and/or event buses.

A minimal setup in pure PHP might look something like this: 
```php
use Nepada\MessageBus\Commands\CommandHandlerLocator;
use Nepada\MessageBus\Commands\MessengerCommandBus;
use Nepada\MessageBus\Events\EventSubscribersLocator;
use Nepada\MessageBus\Events\MessengerEventDispatcher;
use Nepada\MessageBus\Logging\LogMessageResolver;
use Nepada\MessageBus\Logging\MessageContextResolver;
use Nepada\MessageBus\Logging\PrivateClassPropertiesExtractor;
use Nepada\MessageBus\Middleware\LoggingMiddleware;
use Nepada\MessageBus\Middleware\PreventNestedHandlingMiddleware;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\DispatchAfterCurrentBusMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

$dispatchAfterCurrentBusMiddleware = new DispatchAfterCurrentBusMiddleware();
$preventNestedHandlingMiddleware = new PreventNestedHandlingMiddleware();
$loggingMiddleware = new LoggingMiddleware(
    new LogMessageResolver(),
    new MessageContextResolver(
        new PrivateClassPropertiesExtractor(),
    ),
    $psrLogger,
);
$handleCommandMiddleware = new HandleMessageMiddleware(
    new CommandHandlerLocator(
        $psrContainer,
        [
            DoSomethingCommand::class => 'doSomethingHandlerServiceName',
        ],
    ),
);
$handleEventMiddleware = new HandleMessageMiddleware(
    new EventSubscribersLocator(
        $psrContainer,
        [
            SomethingHappenedEvent::class => [
                'doSomethingOnSomethingHappenedServiceName',
                'doSomethingElseOnSomethingHappenedServiceName',
            ],
        ],
    ),
);
$eventDispatcher = new MessengerEventDispatcher(
    new MessageBus([
        $dispatchAfterCurrentBusMiddleware,
        $loggingMiddleware,
        $handleEventMiddleware,
    ]),
);
$commandBus = new MessengerCommandBus(
    new MessageBus([
        $dispatchAfterCurrentBusMiddleware,
        $loggingMiddleware,
        $preventNestedHandlingMiddleware,
        $handleCommandMiddleware,
    ]),
);
```
Note the usage of `DispatchAfterCurrentBusMiddleware` - this is necessary to ensure that events produced during the handling of a command are handled only after the command handling **successfully** finishes.


For Nette Framework integration, consider using [nepada/message-bus-nette](https://github.com/nepada/message-bus-nette).

### Extensions

- [nepada/message-bus-doctrine](https://github.com/nepada/message-bus-doctrine) Doctrine ORM integration - transaction handling, collecting and emitting domain events from entities, etc.
- [nepada/message-bus-nette](https://github.com/nepada/message-bus-nette) Nette Framework DI extension.
- [nepada/phpstan-message-bus](https://github.com/nepada/phpstan-message-bus) adding support for analyzing checked exceptions thrown out of command handlers using [pepakriz/phpstan-exception-rules](https://github.com/pepakriz/phpstan-exception-rules). 


Credits
-------

Static analysis part of the code base and a lot of other core ideas are borrowed from damejidlo/message-bus, originally developed by [Ondřej Bouda](mailto:ondrej.bouda@gmail.com). 
