<?php
declare(strict_types = 1);

namespace Nepada\MessageBus\StaticAnalysis\Rules;

use Nepada\MessageBus\StaticAnalysis\StaticAnalysisFailedException;
use ReflectionMethod;

final class MethodHasOneParameterRule
{

    /**
     * @param ReflectionMethod $method
     * @throws StaticAnalysisFailedException
     */
    public function validate(ReflectionMethod $method): void
    {
        if ($method->getNumberOfParameters() !== 1) {
            throw StaticAnalysisFailedException::with(
                sprintf('Method "%s" must have exactly one parameter', $method->getName()),
                $method->getDeclaringClass()->getName(),
            );
        }
    }

}
