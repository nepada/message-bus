<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands;

use Nepada\MessageBus\StaticAnalysis\ConfigurableHandlerValidator;
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageHandlerValidationConfiguration;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotReadOnlyHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\ValidReadOnlyHandler;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';


/**
 * @testCase
 */
class ReadOnlyCommandValidatorTest extends TestCase
{

    public function testReadOnlyNotEnforcedByDefault(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command());

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(NotReadOnlyHandler::class));
        });
    }

    public function testReadOnlyValidationFails(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command(true));

        Assert::exception(
            function () use ($validator): void {
                $validator->validate(HandlerType::fromString(NotReadOnlyHandler::class));
            },
            StaticAnalysisFailedException::class,
            'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotReadOnlyCommand": '
            . 'Class must be readonly',
        );
    }

    public function testReadOnlyValidationSucceeds(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command(true));

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(ValidReadOnlyHandler::class));
        });
    }

}



(new ReadOnlyCommandValidatorTest())->run();
