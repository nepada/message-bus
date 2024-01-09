<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Events;

use Nepada\MessageBus\StaticAnalysis\ConfigurableHandlerValidator;
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageHandlerValidationConfiguration;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\DoSomethingOnNotReadOnly;
use NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\DoSomethingOnSomethingValidHappened;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';


/**
 * @testCase
 */
class ReadOnlyEventValidatorTest extends TestCase
{

    public function testReadOnlyValidationFails(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::event());

        Assert::exception(
            function () use ($validator): void {
                $validator->validate(HandlerType::fromString(DoSomethingOnNotReadOnly::class));
            },
            StaticAnalysisFailedException::class,
            'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Events\Fixtures\NotReadOnlyEvent": '
            . 'Class must be readonly',
        );
    }

    public function testReadOnlyValidationSucceeds(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::event());

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(DoSomethingOnSomethingValidHappened::class));
        });
    }

}



(new ReadOnlyEventValidatorTest())->run();
