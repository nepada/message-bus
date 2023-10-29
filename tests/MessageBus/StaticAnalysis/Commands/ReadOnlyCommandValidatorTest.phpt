<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands;

use Nepada\MessageBus\StaticAnalysis\ConfigurableHandlerValidator;
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageHandlerValidationConfiguration;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotReadOnlyHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\StaticPropertiesHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\ValidReadOnly81Handler;
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

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(StaticPropertiesHandler::class));
        });
    }

    public function testReadOnlyValidationFails(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command(true));

        Assert::exception(
            function () use ($validator): void {
                $validator->validate(HandlerType::fromString(StaticPropertiesHandler::class));
            },
            StaticAnalysisFailedException::class,
            'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\StaticPropertiesCommand": '
            . 'Readonly class cannot have static properties',
        );

        Assert::exception(
            function () use ($validator): void {
                $validator->validate(HandlerType::fromString(NotReadOnlyHandler::class));
            },
            StaticAnalysisFailedException::class,
            'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotReadOnlyCommand": '
            . 'Property invalid must be readonly',
        );
    }

    public function testReadOnlyValidationSucceedsOnPhp81(): void
    {
        if (PHP_VERSION_ID >= 8_02_00) {
            $this->skip();
        }

        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command(true));

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(ValidReadOnly81Handler::class));
        });
    }

    public function testReadOnlyValidationSucceeds(): void
    {
        if (PHP_VERSION_ID < 8_02_00) {
            $this->skip();
        }

        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command(true));

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(ValidReadOnlyHandler::class));
        });

        Assert::error(
            function () use ($validator): void {
                $validator->validate(HandlerType::fromString(ValidReadOnly81Handler::class));
            },
            E_USER_DEPRECATED,
            'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\ValidReadOnly81Command": '
            . 'Class must be readonly',
        );
    }

}



(new ReadOnlyCommandValidatorTest())->run();
