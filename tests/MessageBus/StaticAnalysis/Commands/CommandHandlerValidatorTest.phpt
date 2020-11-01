<?php
declare(strict_types = 1);

namespace NepadaTests\MessageBus\StaticAnalysis\Commands;

use Nepada\MessageBus\StaticAnalysis\ConfigurableHandlerValidator;
use Nepada\MessageBus\StaticAnalysis\HandlerType;
use Nepada\MessageBus\StaticAnalysis\MessageHandlerValidationConfiguration;
use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\CommandHasIncorrectNameHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\CommandNameDoesNotMatchHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasIncorrectlyNamedParameterHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasIncorrectReturnTypeHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasMoreParametersHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasNoParameterHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasNoReturnTypeHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasParameterWithIncorrectTypeHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\MissingHandleMethodHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotFinalCommandHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotFinalHandler;
use NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\ValidHandler;
use NepadaTests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';


/**
 * @testCase
 */
class CommandHandlerValidatorTest extends TestCase
{

    public function testValidateSucceeds(): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command());

        Assert::noError(function () use ($validator): void {
            $validator->validate(HandlerType::fromString(ValidHandler::class));
        });
    }

    /**
     * @dataProvider getDataForValidateFails
     * @param string $handlerClassName
     * @param string|NULL $expectedExceptionMessage
     */
    public function testValidateFails(string $handlerClassName, ?string $expectedExceptionMessage = null): void
    {
        $validator = new ConfigurableHandlerValidator(MessageHandlerValidationConfiguration::command());

        Assert::exception(
            function () use ($validator, $handlerClassName): void {
                $validator->validate(HandlerType::fromString($handlerClassName));
            },
            StaticAnalysisFailedException::class,
            $expectedExceptionMessage,
        );
    }

    /**
     * @return mixed[][]
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
                NotFinalHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotFinalHandler": '
                . 'Class must be final.',
            ],
            [
                MissingHandleMethodHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\MissingHandleMethodHandler": '
                . 'Method "__invoke" does not exist',
            ],
            [
                HandleMethodHasNoParameterHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasNoParameterHandler": '
                . 'Method "__invoke" must have exactly one parameter',
            ],
            [
                HandleMethodHasMoreParametersHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasMoreParametersHandler": '
                . 'Method "__invoke" must have exactly one parameter',
            ],
            [
                HandleMethodHasIncorrectlyNamedParameterHandler::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasIncorrectlyNamedParameterHandler": '
                . 'Method parameter name must be "command"',
            ],
            [
                HandleMethodHasParameterWithIncorrectTypeHandler::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasParameterWithIncorrectTypeHandler": '
                . 'Method parameter "command" must be of type "Nepada\MessageBus\Commands\Command"',
            ],
            [
                HandleMethodHasNoReturnTypeHandler::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasNoReturnTypeHandler": '
                . 'Method "__invoke" return type must be void',
            ],
            [
                HandleMethodHasIncorrectReturnTypeHandler::class,
                'Static analysis failed for class '
                . '"NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\HandleMethodHasIncorrectReturnTypeHandler": '
                . 'Method "__invoke" return type must be void',
            ],
            [
                NotFinalCommandHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\NotFinalCommand": '
                . 'Class must be final.',
            ],
            [
                CommandHasIncorrectNameHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\IncorrectName": '
                . 'Class must have suffix "Command"',
            ],
            [
                CommandNameDoesNotMatchHandler::class,
                'Static analysis failed for class "NepadaTests\MessageBus\StaticAnalysis\Commands\Fixtures\CommandNameDoesNotMatchHandler": '
                . 'Message handler must match message name. Expected name: "#^ValidHandler$#"',
            ],
        ];
    }

}



(new CommandHandlerValidatorTest())->run();
