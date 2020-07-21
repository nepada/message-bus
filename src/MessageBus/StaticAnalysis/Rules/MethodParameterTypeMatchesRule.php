<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use ReflectionParameter;

final class MethodParameterTypeMatchesRule
{

    private string $parameterType;

    public function __construct(string $parameterType)
    {
        $this->parameterType = $parameterType;
    }

    /**
     * @param ReflectionParameter $parameter
     * @throws StaticAnalysisFailedException
     */
    public function validate(ReflectionParameter $parameter): void
    {
        $parameterType = $parameter->getType();
        if ($parameterType === null) {
            throw $this->createException($parameter);
        }

        $parameterTypeName = $parameterType->getName();
        if ($parameterTypeName !== $this->parameterType && ! is_subclass_of($parameterTypeName, $this->parameterType)) {
            throw $this->createException($parameter);
        }
    }

    private function createException(ReflectionParameter $parameter): StaticAnalysisFailedException
    {
        $class = $parameter->getDeclaringClass();
        if ($class === null) {
            throw new \LogicException('Class must be set in this context.');
        }

        return StaticAnalysisFailedException::with(
            sprintf('Method parameter "%s" must be of type "%s"', $parameter->getName(), $this->parameterType),
            $class->getName(),
        );
    }

}
