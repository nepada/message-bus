<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis;

use Nepada\MessageBus\StaticAnalysis\Rules\ClassExistsRule;
use Nepada\MessageBus\StaticAnalysis\Rules\ClassHasPublicMethodRule;
use Nepada\MessageBus\StaticAnalysis\Rules\ClassIsFinalRule;
use Nepada\MessageBus\StaticAnalysis\Rules\ClassIsReadOnlyRule;
use Nepada\MessageBus\StaticAnalysis\Rules\ClassNameHasSuffixRule;
use Nepada\MessageBus\StaticAnalysis\Rules\MethodHasOneParameterRule;
use Nepada\MessageBus\StaticAnalysis\Rules\MethodParameterNameMatchesRule;
use Nepada\MessageBus\StaticAnalysis\Rules\MethodParameterTypeMatchesRule;
use Nepada\MessageBus\StaticAnalysis\Rules\MethodReturnTypeIsVoidRule;
use Nepada\MessageBus\StaticAnalysis\Rules\ShortClassNameMatchesRule;

final readonly class ConfigurableHandlerValidator implements MessageHandlerValidator
{

    private MessageHandlerValidationConfiguration $configuration;

    private MessageTypeExtractor $messageTypeExtractor;

    public function __construct(MessageHandlerValidationConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->messageTypeExtractor = new MessageTypeExtractor();
    }

    public function validate(HandlerType $handlerType): void
    {
        $handlerClass = $handlerType->toString();

        (new ClassExistsRule())->validate($handlerClass);

        if ($this->configuration->shouldHandlerClassBeFinal()) {
            (new ClassIsFinalRule())->validate($handlerClass);
        }

        $handleMethodName = MessageTypeExtractor::METHOD_NAME;
        (new ClassHasPublicMethodRule($handleMethodName))->validate($handlerClass);

        $handleMethod = ReflectionHelper::requireMethodReflection($handlerClass, $handleMethodName);
        (new MethodHasOneParameterRule())->validate($handleMethod);

        $parameter = $handleMethod->getParameters()[0];
        $parameterName = $this->configuration->getHandleMethodParameterName();
        (new MethodParameterNameMatchesRule($parameterName))->validate($parameter);
        $parameterType = $this->configuration->getHandleMethodParameterType();
        (new MethodParameterTypeMatchesRule($parameterType))->validate($parameter);

        (new MethodReturnTypeIsVoidRule())->validate($handleMethod);

        $messageType = $this->messageTypeExtractor->extract($handlerType);

        if ($this->configuration->shouldMessageClassBeFinal()) {
            (new ClassIsFinalRule())->validate($messageType->toString());
        }

        if ($this->configuration->shouldMessageClassBeReadOnly()) {
            (new ClassIsReadOnlyRule())->validate($messageType->toString());
        }

        $messageClassSuffix = $this->configuration->getMessageClassSuffix();
        (new ClassNameHasSuffixRule($messageClassSuffix))->validate($messageType->toString());
        $shortMessageName = $messageType->shortName($messageClassSuffix);

        $this->validateHandlerClassName($handlerClass, $shortMessageName, $this->configuration);
    }

    /**
     * @throws StaticAnalysisFailedException
     */
    private function validateHandlerClassName(string $handlerClass, string $shortMessageName, MessageHandlerValidationConfiguration $configuration): void
    {
        $expectedHandlerClassShort = sprintf(
            '#^%s%s%s$#',
            $configuration->getHandlerClassPrefixRegex(),
            $shortMessageName,
            $configuration->getHandlerClassSuffix(),
        );

        try {
            (new ShortClassNameMatchesRule($expectedHandlerClassShort))->validate($handlerClass);
        } catch (StaticAnalysisFailedException $exception) {
            throw StaticAnalysisFailedException::with(
                sprintf(
                    'Message handler must match message name. Expected name: "%s"',
                    $expectedHandlerClassShort,
                ),
                $handlerClass,
            );
        }
    }

}
