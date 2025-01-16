<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events;

use Nepada\MessageBus\StaticAnalysis\ConfigurableHandlerValidator;
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageHandlerValidationConfiguration;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\DoSomethingOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\EventHasIncorrectNameOnIncorrectName;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\EventNameDoesNotMatchSubscriber;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasIncorrectlyNamedParameterOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasIncorrectReturnTypeOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasMoreParametersOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasNoParameterOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasNullReturnTypeOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasParameterWithIncorrectTypeOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\MissingHandleOnSomethingValidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\NotFinalEventOnSomethingInvalidHappened;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\NotFinalOnSomethingValidHappened;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';


/**
 * @testCase
 */
class EventSubscriberValidatorTest extends TestCase
{

    public function testValidateSucceeds(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::event());

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(DoSomethingOnSomethingValidHappened::class));
        });
    }

    /**
     * @dataProvider getDataForValidateFails
     * @param string|NULL $expectedExceptionMessage
     */
    public function testValidateFails(string $subscriberClassName, ?string $expectedExceptionMessage = null): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::event());

        Assert::exception(function () use ($validator, $subscriberClassName): void {
            $validator->validate(HandlerType::fromString($subscriberClassName));
        }, StaticAnalysisFailedException::class, $expectedExceptionMessage);
    }

    /**
     * @return list<mixed[]>
     */
    public function getDataForValidateFails(): array
    {
        return [
            [
                'NonexistentClass',
                'Static analysis failed for class "NonexistentClass": '
                . 'Class does not exist',
            ],
            [
                NotFinalOnSomethingValidHappened::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\NotFinalOnSomethingValidHappened": '
                . 'Class must be final.',
            ],
            [
                MissingHandleOnSomethingValidHappened::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\MissingHandleOnSomethingValidHappened": '
                . 'Method "__invoke" does not exist',
            ],
            [
                HandleMethodHasNoParameterOnSomethingValidHappened::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasNoParameterOnSomethingValidHappened": '
                . 'Method "__invoke" must have exactly one parameter',
            ],
            [
                HandleMethodHasMoreParametersOnSomethingValidHappened::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasMoreParametersOnSomethingValidHappened": '
                . 'Method "__invoke" must have exactly one parameter',
            ],
            [
                HandleMethodHasIncorrectlyNamedParameterOnSomethingValidHappened::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasIncorrectlyNamedParameterOnSomethingValidHappened": '
                . 'Method parameter name must be "event"',
            ],
            [
                HandleMethodHasParameterWithIncorrectTypeOnSomethingValidHappened::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasParameterWithIncorrectTypeOnSomethingValidHappened": '
                . 'Method parameter "event" must be of type "Nepada\MessageBus\Events\Event"',
            ],
            [
                HandleMethodHasNullReturnTypeOnSomethingValidHappened::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasNullReturnTypeOnSomethingValidHappened": '
                . 'Method "__invoke" return type must be void',
            ],
            [
                HandleMethodHasIncorrectReturnTypeOnSomethingValidHappened::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\HandleMethodHasIncorrectReturnTypeOnSomethingValidHappened": '
                . 'Method "__invoke" return type must be void',
            ],
            [
                NotFinalEventOnSomethingInvalidHappened::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\SomethingInvalidHappenedEvent": '
                . 'Class must be final.',
            ],
            [
                EventHasIncorrectNameOnIncorrectName::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\IncorrectName": '
                . 'Class must have suffix "Event"',
            ],
            [
                EventNameDoesNotMatchSubscriber::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\EventNameDoesNotMatchSubscriber": '
                . 'Message handler must match message name. Expected name: "#^(.+)OnSomethingValidHappened$#"',
            ],
        ];
    }

}



(new EventSubscriberValidatorTest())->run();
